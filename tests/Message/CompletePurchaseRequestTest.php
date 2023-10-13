<?php

declare(strict_types=1);

namespace Omnipay\Jazzcash\Tests\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Jazzcash\Message\AbstractRequest;
use Omnipay\Jazzcash\Message\CompletePurchaseRequest;
use Omnipay\Jazzcash\Message\CompletePurchaseResponse;
use Omnipay\Jazzcash\Tests\Helper;
use Omnipay\Tests\TestCase;

final class CompletePurchaseRequestTest extends TestCase
{
    private AbstractRequest $request;
    private array $options = [];
    private array $parameters = [];

    public function setUp(): void
    {
        $this->options = Helper::getOptions();

        $content = $this->getMockHttpResponse('CompletePurchase_Success.txt')->getBody()->getContents();

        $this->parameters = json_decode($content, true);


        $this->request = new CompletePurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize($this->options);
    }

    public function test_it_has_valid_data(): void
    {
        $this->getHttpRequest()->initialize($this->parameters);

        $this->assertSame([
            "ppmbf_1" => null,
            "ppmbf_2" => null,
            "ppmbf_3" => null,
            "ppmbf_4" => null,
            "ppmbf_5" => null,
            "ppmpf_1" => null,
            "ppmpf_2" => "zu1vv3",
            "ppmpf_3" => null,
            "ppmpf_4" => null,
            "ppmpf_5" => null,
            "pp_Amount" => "1500",
            "pp_BankID" => null,
            "pp_TxnType" => "MWALLET",
            "pp_Version" => "1.1",
            "pp_AuthCode" => "024166247497",
            "pp_Language" => "EN",
            "pp_TxnRefNo" => "6158408e95098",
            "pp_MerchantID" => "00258027",
            "pp_SecureHash" => "19DAB816503B0EAABE2C48FBE48461CB2ADF685BD417EBEE37C080951DB0F528",
            "pp_TxnCurrency" => "PKR",
            "pp_TxnDateTime" => "20211002162046",
            "pp_ResponseCode" => "000",
            "pp_BillReference" => "6158408e95098",
            "pp_SubMerchantId" => null,
            "pp_ResponseMessage" => null,
            "pp_SettlementExpiry" => null,
            "pp_RetreivalReferenceNo" => "211002644299",
        ], $this->request->getData());
    }

    public function test_it_construct_valid_response(): void
    {
        $this->assertInstanceOf(
            CompletePurchaseResponse::class,
            $this->request->createResponse($this->parameters)
        );
    }

    public function test_it_require_data(): void
    {
        $parameters = [];

        $this->getHttpRequest()->initialize($parameters);

        try {
            $this->request->getData();
        } catch (InvalidRequestException $exception) {
            $this->assertSame('The request data is required', $exception->getMessage());
        }
    }

    public function test_it_has_valid_request_hash(): void
    {
        $parameters = [
            ...$this->parameters
        ];

        unset($parameters['pp_SecureHash']);

        $this->getHttpRequest()->initialize($parameters);

        try {
            $this->request->getData();
        } catch (InvalidRequestException $exception) {
            $this->assertSame('The request data is invalid', $exception->getMessage());
        }
    }

    public function test_it_has_request_hash_mismatch(): void
    {
        $parameters = [
            ...$this->parameters
        ];

        $parameters['pp_SecureHash'] = str_pad('', 64);

        $this->getHttpRequest()->initialize($parameters);

        try {
            $this->request->getData();
        } catch (InvalidRequestException $exception) {
            $this->assertSame('The request hash is invalid', $exception->getMessage());
        }
    }
}
