<?php

declare(strict_types=1);

namespace Omnipay\Jazzcash\Tests\Message;

use Omnipay\Jazzcash\Message\AbstractRequest;
use Omnipay\Jazzcash\Message\PurchaseRequest;
use Omnipay\Jazzcash\Tests\Helper;
use Omnipay\Tests\TestCase;

final class PurchaseRequestTest extends TestCase
{
    private AbstractRequest $request;
    private array $parameters = [];

    public function setUp(): void
    {
        $this->parameters = [
            ...Helper::getOptions(),
            ...Helper::getParameters()
        ];

        $this->request = new PurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );
    }

    public function test_it_has_valid_data(): void
    {
        $this->request->initialize($this->parameters);

        $this->assertSame(
            [
            'pp_Version' => '1.1',
            'pp_MerchantID' => 'merchant123',
            'pp_Password' => 'password',
            'pp_TxnType' => 'MWALLET',
            'pp_TxnRefNo' => '647cb56c890e4',
            'pp_Language' => 'EN',
            'pp_Amount' => '2000',
            'pp_TxnCurrency' => 'PKR',
            'pp_TxnDateTime' => $this->parameters['transactionTimestamp']->format('YmdHis'),
            'pp_BillReference' => '647cb56c890e4',
            'pp_Description' => 'Test Order',
            'pp_TxnExpiryDateTime' => $this->parameters['transactionExpiryTimestamp']->format('YmdHis'),
            'pp_ReturnURL' => 'https://example.com/return',
            'ppmpf_2' => 'abcdef',
            'pp_SecureHash' => '876B465D21973A96DD4DC7BE69EC129FA1FE529B9CC0FA1D75C8CD6534613FBC',
            ], $this->request->getData()
        );

        $this->request->setPaymentMethod('OTC');

        $this->assertSame('OTC', $this->request->getData()['pp_TxnType']);
    }
}
