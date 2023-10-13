<?php

declare(strict_types=1);

namespace Omnipay\Jazzcash\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class CompletePurchaseRequest extends AbstractRequest
{
    protected array $required = [];

    public function getData(): array
    {
        $data = match ($this->httpRequest->getMethod()) {
            'GET' => $this->httpRequest->query->all(),
            'POST' => $this->httpRequest->request->all(),
            default => []
        };

        $this->validateRequestData($data);

        return $data;
    }

    public function createResponse(array $data): CompletePurchaseResponse
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    protected function validateRequestData(array $data = []): void
    {
        if (empty($data)) {
            throw new InvalidRequestException('The request data is required');
        }

        if (!isset($data['pp_SecureHash'])) {
            throw new InvalidRequestException('The request data is invalid');
        }

        if (!$this->validateHash($data)) {
            throw new InvalidRequestException('The request hash is invalid');
        }
    }

    protected function validateHash(array $data): bool
    {
        $hashAble = [];

        foreach ($data as $key => $value) {
            if ($key !== 'pp_SecureHash' && !empty($value)) {
                $hashAble[$key] = $value;
            }
        }

        return $this->makeHash($hashAble) === $data['pp_SecureHash'];
    }
}
