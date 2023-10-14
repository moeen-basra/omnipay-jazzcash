<?php

declare(strict_types=1);

namespace Omnipay\Jazzcash\Tests;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Jazzcash\Gateway;
use Omnipay\Jazzcash\Message\CompletePurchaseRequest;
use Omnipay\Jazzcash\Message\CompletePurchaseResponse;
use Omnipay\Jazzcash\Message\FetchTransactionRequest;
use Omnipay\Jazzcash\Message\FetchTransactionResponse;
use Omnipay\Jazzcash\Message\PurchaseRequest;
use Omnipay\Jazzcash\Message\PurchaseResponse;
use Omnipay\Tests\TestCase;

class GatewayTest extends TestCase
{
    protected AbstractGateway $gateway;


    protected array $options = [];

    protected array $parameters = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = Helper::getOptions();
        $this->parameters = Helper::getPurchaseParameters();

        $this->gateway->initialize($this->options);
    }

    public function test_gateway_allow_purchase_request(): void
    {
        $this->assertTrue($this->gateway->supportsPurchase());
    }

    public function test_purchase_request_construct_valid(): void
    {
        $request = $this->gateway->purchase($this->parameters);

        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertArrayHasKey('pp_Amount', $request->getData());
    }

    public function test_purchase_parameters(): void
    {
        foreach ($this->gateway->getDefaultParameters() as $key => $default) {
            // set property on gateway
            $getter = 'get' . ucfirst($this->camelCase($key));
            $setter = 'set' . ucfirst($this->camelCase($key));
            $value = uniqid('', true);
            $this->gateway->$setter($value);

            // request should have matching property, with correct value
            $request = $this->gateway->purchase($this->parameters);

            $this->assertSame($this->parameters[$key], $request->$getter());
        }
    }

    public function test_purchase_success(): void
    {
        $response = $this->gateway->purchase($this->parameters)->send();

        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertInstanceOf(RedirectResponseInterface::class, $response);

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame(
            'https://payments.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform',
            $response->getRedirectUrl()
        );

        $this->gateway->setTestMode(true);

        $response = $this->gateway->purchase($this->parameters)->send();

        $this->assertSame(
            'https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform',
            $response->getRedirectUrl()
        );
    }

    public function test_gateway_allow_complete_purchase_request(): void
    {
        $this->assertTrue($this->gateway->supportsCompletePurchase());
    }

    public function test_gateway_complete_purchase_success(): void
    {
        $content = $this->getMockHttpResponse('CompletePurchase_Success.txt')->getBody()->getContents();

        $parameters = json_decode($content, true);

        $this->getHttpRequest()->initialize($parameters);

        $request = $this->gateway->completePurchase($this->options);

        $this->assertInstanceOf(CompletePurchaseRequest::class, $request);

        $response = $request->send();

        $this->assertInstanceOf(CompletePurchaseResponse::class, $response);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($parameters['pp_TxnRefNo'], $response->getTransactionReference());
    }

    public function test_gateway_allow_fetch_request(): void
    {
        $this->assertTrue($this->gateway->supportsFetchTransaction());
    }

    public function test_gateway_fetch_transaction_success(): void
    {
        $this->setMockHttpResponse('FetchTransaction_Success.txt');

        $parameters = Helper::getFetchTransactionParameters();
        $request = $this->gateway->fetchTransaction($parameters);

        $this->assertInstanceOf(
            FetchTransactionRequest::class,
            $request
        );

        $response = $request->send();

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);
        $this->assertInstanceOf(NotificationInterface::class, $response);

        $this->assertSame(
            'Thank you for Using JazzCash, your operation successfully completed.',
            $response->getMessage()
        );

        $this->assertSame(
            NotificationInterface::STATUS_COMPLETED,
            $response->getTransactionStatus()
        );

        $this->assertSame(
            $parameters['transactionReference'],
            $response->getTransactionReference()
        );

        $this->assertTrue($response->isSuccessful());
    }

    public function test_fetch_transaction_failed(): void
    {
        $this->setMockHttpResponse('FetchTransaction_Failed.txt');

        $parameters = Helper::getFetchTransactionParameters();
        $response = $this->gateway->fetchTransaction($parameters)->send();

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);
        $this->assertInstanceOf(NotificationInterface::class, $response);

        $this->assertSame(
            NotificationInterface::STATUS_FAILED,
            $response->getTransactionStatus()
        );

        $this->assertSame(
            $parameters['transactionReference'],
            $response->getTransactionReference()
        );

        $this->assertFalse($response->isSuccessful());
    }
}
