<?php

declare(strict_types=1);

namespace Omnipay\Jazzcash\Tests\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Jazzcash\Message\AbstractRequest;
use Omnipay\Jazzcash\Message\PurchaseRequest;
use Omnipay\Tests\TestCase;

final class AbstractRequestTest extends TestCase
{
    private AbstractRequest $request;

    public function setUp(): void
    {
        $this->request = new PurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );
    }

    public function test_it_sets_only_allowed_payment_method(): void
    {
        $methods = ['MWALLET', 'OTC', 'MPAY'];

        foreach ($methods as $method) {
            $this->request->setPaymentMethod($method);

            $this->assertSame($method, $this->request->getPaymentMethod());
        }

        try {
            $this->request->setPaymentMethod('PaymentMethod');
        } catch (InvalidRequestException $exception) {
            $this->assertStringContainsString('The paymentMethod "PaymentMethod" is invalid', $exception->getMessage());
        }
    }

    public function test_it_sets_only_allowed_language(): void
    {
        $this->request->setLanguage('EN');

        $this->assertSame('EN', $this->request->getLanguage());

        try {
            $this->request->setLanguage('DE');
        } catch (InvalidRequestException $exception) {
            $this->assertStringContainsString('The language "DE" is invalid', $exception->getMessage());
        }
    }

    public function test_it_sets_only_allowed_currency(): void
    {
        $this->request->setCurrency('PKR');

        $this->assertSame('PKR', $this->request->getCurrency());

        try {
            $this->request->setCurrency('AUD');
        } catch (InvalidRequestException $exception) {
            $this->assertStringContainsString('The currency "AUD" is invalid', $exception->getMessage());
        }
    }

    public function test_it_generate_valid_endpoints(): void
    {
        $this->request->setTestMode(false);

        $this->assertSame(
            'https://payments.jazzcash.com.pk',
            $this->request->getEndPoint()
        );

        $this->request->setTestMode(true);

        $this->assertSame(
            'https://sandbox.jazzcash.com.pk',
            $this->request->getEndPoint()
        );
    }

    public function test_it_sets_valid_extra_data_fields(): void
    {
        $extra = [
            'field_1' => 'value_1',
            'field_2' => 'value_2',
        ];

        $this->request->setExtra($extra);

        $this->assertSame($extra, $this->request->getExtra());
        $this->assertSame('value_1', $this->request->getExtra('field_1'));
        $this->assertSame(null, $this->request->getExtra('field_3'));
    }
}
