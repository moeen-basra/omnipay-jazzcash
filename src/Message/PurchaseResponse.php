<?php

namespace Omnipay\Jazzcash\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @inheritDoc
     */
    public function isSuccessful(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectMethod(): string
    {
        return 'POST';
    }

    public function isRedirect(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectData(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUrl(): string
    {
        return $this->request->getEndPoint() . '/CustomerPortal/transactionmanagement/merchantform';
    }
}
