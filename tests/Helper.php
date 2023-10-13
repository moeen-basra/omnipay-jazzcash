<?php

declare(strict_types=1);

namespace Omnipay\Jazzcash\Tests;

final class Helper
{
    public static function getOptions(array $options = []): array
    {
        return [
            'merchantId' => 'merchant123',
            'password' => 'password',
            'secretKey' => 'reallySecretKey',
            ...$options
        ];
    }

    public static function getParameters(array $parameters = []): array
    {
        return [
            'paymentMethod' => 'MWALLET',
            'transactionTimestamp' => new \DateTime('2023-10-11'),
            'transactionExpiryTimestamp' => new \DateTime('2023-10-12'),
            'billReference' => '647cb56c890e4',
            'description' => 'Test Order',
            'amount' => '20',
            'language' => 'EN',
            'currency' => 'PKR',
            'transactionId' => '647cb56c890e4',
            'extra' => [
                'field_2' => 'abcdef',
            ],
            'returnUrl' => 'https://example.com/return',
            ...$parameters
        ];
    }

    public static function getPurchaseParameters(array $parameters = []): array
    {
        return [
            ...self::getOptions(),
            ...self::getParameters(),
            ...$parameters
        ];
    }

    public static function getFetchTransactionParameters(array $parameters = []): array
    {
        return [
            'transactionReference' => '647cb56c890e4',
            ...$parameters
        ];
    }
}
