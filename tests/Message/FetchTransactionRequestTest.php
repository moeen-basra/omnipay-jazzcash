<?php

declare(strict_types=1);

namespace Omnipay\Jazzcash\Tests\Message;

use Omnipay\Jazzcash\Message\AbstractRequest;
use Omnipay\Jazzcash\Message\FetchTransactionRequest;
use Omnipay\Jazzcash\Tests\Helper;
use Omnipay\Tests\TestCase;

final class FetchTransactionRequestTest extends TestCase
{
    private AbstractRequest $request;
    private array $parameters = [];

    public function setUp(): void
    {
        $this->parameters = [
            ...Helper::getOptions(),
            ...Helper::getFetchTransactionParameters()
        ];

        $this->request = new FetchTransactionRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );
    }

    public function test_fetch_request_get_uri_return_valid_uri(): void
    {
        $this->request->setTestMode(false);

        $this->assertSame(
            'https://payments.jazzcash.com.pk/ApplicationAPI/API/PaymentInquiry/Inquire',
            $this->request->getUri()
        );

        $this->request->setTestMode(true);

        $this->assertSame(
            'https://sandbox.jazzcash.com.pk/ApplicationAPI/API/PaymentInquiry/Inquire',
            $this->request->getUri()
        );
    }

    public function test_fetch_request_return_valid_data(): void
    {
        $this->request->initialize($this->parameters);

        $this->assertSame([
            'pp_TxnRefNo' => $this->parameters['transactionReference'],
            'pp_MerchantID' => $this->parameters['merchantId'],
            'pp_Password' => $this->parameters['password'],
            'pp_Version' => '1.1',
            'pp_SecureHash' => 'B39B22919994EA5C9BFDD02940DF6E87EE06441B778DD4D68709F8204E6404CD',
        ], $this->request->getData());
    }
}
