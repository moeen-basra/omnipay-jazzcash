<?php

declare(strict_types=1);

namespace Omnipay\Jazzcash\Message;

use Omnipay\Common\Message\NotificationInterface;

class FetchTransactionResponse extends AbstractResponse implements NotificationInterface
{
    /**
     * The expected transaction status coming from jazzcash
     * @var array|string[]
     */
    protected array $transactionStatuses = [
        'Pending',
        'Completed',
        'Dropped',
        'Failed',
        'Validation Failed',
        ''
    ];

    public function isSuccessful(): bool
    {
        if (isset($this->data['pp_ResponseCode']) && $this->data['pp_ResponseCode'] === '000') {
            return true;
        }
        return false;
    }

    public function getTransactionReference(): string
    {
        return $this->request->getParameters()['transactionReference'];
    }

    public function getTransactionStatus(): string
    {
        $status = !empty($this->data['pp_Status']) ? $this->data['pp_Status'] : 'UNKNOWN';

        return match ($status) {
            'Pending' => NotificationInterface::STATUS_PENDING,
            'Completed' => NotificationInterface::STATUS_COMPLETED,
            default => NotificationInterface::STATUS_FAILED
        };
    }

    public function getMessage(): string
    {
        if (!empty($this->data['pp_ResponseMessage'])) {
            return $this->data['pp_ResponseMessage'];
        }

        // This can be logged
        return 'UNKNOWN';
    }
}
