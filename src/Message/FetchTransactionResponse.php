<?php

declare(strict_types=1);

namespace Omnipay\Jazzcash\Message;

class FetchTransactionResponse extends AbstractResponse
{
    protected array $responseCodes = [
        '000',
        '199',
        '110'
    ];

    protected array $statuses = [
        'Pending',
        'Completed',
        'Dropped',
        'Failed',
        'Validation Failed',
    ];

    public function isSuccessful(): bool
    {
        if (isset($this->data['pp_ResponseCode']) && $this->data['pp_ResponseCode'] === '000') {
            return true;
        }
        return false;
    }
}

