<?php

namespace Omnipay\Jazzcash\Message;

use DateTime;

class PurchaseRequest extends AbstractRequest
{

    protected array $required = [
        'merchantId',
        'password',
        'secretKey',
        'currency',
        'language',
        'paymentMethod',
        'transactionId',
        'transactionTimestamp',
        'transactionExpiryTimestamp',
        'billReference',
        'description',
        'returnUrl',
    ];

    public function setTransactionTimestamp(DateTime $value): static
    {
        return $this->setParameter('transactionTimestamp', $value);
    }

    public function getTransactionTimestamp(): DateTime
    {
        return $this->getParameter('transactionTimestamp');
    }

    public function setTransactionExpiryTimestamp(DateTime $value): static
    {
        return $this->setParameter('transactionExpiryTimestamp', $value);
    }

    public function getTransactionExpiryTimestamp(): DateTime
    {
        return $this->getParameter('transactionExpiryTimestamp');
    }

    public function setBillReference(string $value): static
    {
        return $this->setParameter('billReference', $value);
    }

    public function getBillReference(): string
    {
        return $this->getParameter('billReference');
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $this->validate(...$this->required);

        $data = array_filter(
            [
                'pp_Version' => $this->version,
                'pp_MerchantID' => $this->getMerchantId(),
                'pp_SubMerchantId' => $this->getSubMerchantId(),
                'pp_Password' => $this->getPassword(),
                'pp_BankID' => $this->getBankId(),
                'pp_TxnType' => $this->getPaymentMethod(),
                'pp_TxnRefNo' => $this->getTransactionId(),
                'pp_Language' => $this->getLanguage(),
                'pp_Amount' => (string)$this->getAmountInteger(),
                'pp_TxnCurrency' => $this->getCurrency(),
                'pp_TxnDateTime' => $this->getTransactionTimestamp()->format('YmdHis'),
                'pp_BillReference' => $this->getBillReference(),
                'pp_Description' => $this->getDescription(),
                'pp_TxnExpiryDateTime' => $this->getTransactionExpiryTimestamp()->format('YmdHis'),
                'pp_ReturnURL' => $this->getReturnUrl(),
                'ppmpf_1' => $this->getExtra('field_1'),
                'ppmpf_2' => $this->getExtra('field_2'),
                'ppmpf_3' => $this->getExtra('field_3'),
                'ppmpf_4' => $this->getExtra('field_4'),
                'ppmpf_5' => $this->getExtra('field_5'),
            ]
        );

        $data['pp_SecureHash'] = $this->makeHash($data);

        return $data;
    }

    public function createResponse(array $data): PurchaseResponse
    {
        return $this->response = (new PurchaseResponse($this, $data));
    }
}
