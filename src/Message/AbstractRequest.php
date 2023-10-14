<?php

namespace Omnipay\Jazzcash\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $zeroAmountAllowed = false;

    protected string $version = '1.1';
    protected string $liveEndpoint = 'https://payments.jazzcash.com.pk';
    protected string $testEndpoint = 'https://sandbox.jazzcash.com.pk';

    protected array $required = [];
    private array $allowedCurrencies = ['PKR'];
    private array $allowedLanguages = ['EN'];
    private array $allowedPaymentMethods = ['MWALLET', 'OTC', 'MPAY'];

    abstract public function createResponse(array $data): AbstractResponse;

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

    public function setBankId(string $value): static
    {
        return $this->setParameter('bankId', $value);
    }

    public function getBankId(): ?string
    {
        return $this->getParameter('bankId');
    }

    public function setSubMerchantId(string $value): static
    {
        return $this->setParameter('subMerchantId', $value);
    }

    public function getSubMerchantId(): ?string
    {
        return $this->getParameter('subMerchantId');
    }

    public function setLanguage(string $value): static
    {
        if (!in_array($value, $this->allowedLanguages)) {
            throw new InvalidRequestException(
                sprintf(
                    'The language "%s" is invalid, must be one of %s',
                    $value,
                    implode(
                        ', ',
                        $this->allowedLanguages
                    )
                )
            );
        }
        return $this->setParameter('language', strtoupper($value));
    }

    public function getLanguage(): string
    {
        return $this->getParameter('language');
    }

    public function setPaymentMethod($value): static
    {
        if (!in_array($value, $this->allowedPaymentMethods)) {
            throw new InvalidRequestException(
                sprintf(
                    'The paymentMethod "%s" is invalid, must be one of %s',
                    $value,
                    implode(
                        ', ',
                        $this->allowedPaymentMethods
                    )
                )
            );
        }
        return $this->setParameter('paymentMethod', $value);
    }

    public function setCurrency($value): static
    {
        if (!in_array($value, $this->allowedCurrencies)) {
            throw new InvalidRequestException(
                sprintf(
                    'The currency "%s" is invalid, must be one of %s',
                    $value,
                    implode(
                        ', ',
                        $this->allowedCurrencies
                    )
                )
            );
        }
        return $this->setParameter('currency', $value);
    }

    public function getEndPoint(): string
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function setExtra(string|array $key, mixed $value = null): static
    {
        $extra = $this->getParameter('extra') ?? [];

        if (is_array($key)) {
            $extra = [
                ...$extra,
                ...$key
            ];
        } else {
            $extra = [
                ...$extra,
                $key => $value
            ];
        }

        return $this->setParameter('extra', $extra);
    }

    public function getExtra(?string $key = null): mixed
    {
        if ($key) {
            if (isset($this->getParameter('extra')[$key])) {
                return $this->getParameter('extra')[$key];
            }
            return null;
        }
        return $this->getParameter('extra') ?? [];
    }

    protected function makeHash(array $params): string
    {
        ksort($params);

        $string = $this->getSecretKey() . '&' . implode('&', array_values($params));

        return strtoupper(
            hash_hmac(
                'sha256',
                mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1, UTF-8'),
                $this->getSecretKey()
            )
        );
    }

    public function sendData($data): ResponseInterface
    {
        return $this->createResponse($data);
    }
}
