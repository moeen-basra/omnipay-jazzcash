<?php

declare(strict_types=1);

namespace Omnipay\Jazzcash\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Throwable;

class FetchTransactionRequest extends AbstractRequest
{

    protected array $required = [
        'version',
        'merchantId',
        'password',
        'transactionReference',

    ];

    public function getUri(): string
    {
        return $this->getEndPoint() . '/ApplicationAPI/API/PaymentInquiry/Inquire';
    }

    public function createResponse(array $data): FetchTransactionResponse
    {
        try {
            $response = $this->httpClient->request(
                'POST', $this->getUri(), [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                ], json_encode($data, JSON_THROW_ON_ERROR)
            );

            $content = json_decode($response->getBody()->getContents(), true);

            return $this->response = new FetchTransactionResponse($this, $content);
        } catch (Throwable $exception) {
            throw new InvalidResponseException($exception->getMessage(), 400, $exception);
        }
    }

    public function getData(): array
    {
        $data = [
            'pp_TxnRefNo' => $this->getTransactionReference(),
            'pp_MerchantID' => $this->getMerchantId(),
            'pp_Password' => $this->getPassword(),
            'pp_Version' => $this->version,
        ];

        $data['pp_SecureHash'] = $this->makeHash($data);

        return $data;
    }
}
