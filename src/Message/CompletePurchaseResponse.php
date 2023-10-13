<?php

declare(strict_types=1);

namespace Omnipay\Jazzcash\Message;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return isset($this->data['pp_ResponseCode']) && $this->data['pp_ResponseCode'] === '000';
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['pp_TxnRefNo'] ?? null;
    }
}
