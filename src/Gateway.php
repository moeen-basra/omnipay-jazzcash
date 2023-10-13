<?php

namespace Omnipay\Jazzcash;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Jazzcash\Message\CompletePurchaseRequest;
use Omnipay\Jazzcash\Message\FetchTransactionRequest;
use Omnipay\Jazzcash\Message\PurchaseRequest;

class Gateway extends AbstractGateway
{
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'Jazzcash';
    }

    /**
     * @inheritDoc
     */
    public function getShortName(): string
    {
        return 'Jazzcash';
    }

    /**
     * @inheritDoc
     */
    public function getDefaultParameters(): array
    {
        return [
            'password' => '',
            'secretKey' => '',
            'merchantId' => '',
        ];
    }

    public function purchase(array $options): AbstractRequest
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function completePurchase(array $options): AbstractRequest
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }

    public function fetchTransaction(array $options): AbstractRequest
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }

    public function setMerchantId(string $value): static
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantId(): string
    {
        return $this->getParameter('merchantId');
    }

    public function setPassword(string $value): static
    {
        return $this->setParameter('password', $value);
    }

    public function getPassword(): string
    {
        return $this->getParameter('password');
    }

    public function setSecretKey(string $value): static
    {
        return $this->setParameter('secretKey', $value);
    }

    public function getSecretKey(): string
    {
        return $this->getParameter('secretKey');
    }
}
