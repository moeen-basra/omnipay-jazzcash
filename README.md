# Omnipay: Jazzcash

**Jazz Jazzcash gateway for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/jazzcash) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements easypaisa support for Omnipay.

## Install

Via Composer

```bash
$ composer require moeen-basra/omnipay-jazzcash
```

## Usage

This gateway provides seamless integration with jazzcash hosted paged integration.


### Purchase Request
```php
use Omnipay\Omnipay;

/** @var \Omnipay\Jazzcash\Gateway $gateway */
$gateway = Omnipay::create('Jazzcash');

// initialize with array
$gateway->initialize([
    'merchantId' => 'your-merchant-id',
    'password' => 'your-password',
    'secretKey' => 'your-secret-key',
]);

// or individual properties setter

$gateway->setMerchantId('your-merchant-id')
    ->setPassword('your-password')
    ->setSecretKey('your-secret-key');

// set the test mode if needed
$gateway->setTestMode(true);

$date = CarbonImmutable::now('Asia/Karachi');

try {
    $parameters = [
        [
            'paymentMethod' => 'MWALLET', // 'MWALLET' | 'OTC' | 'MPAY' 
            'transactionTimestamp' => new \DateTime(), // make sure not past date also check your server timezone
            'transactionExpiryTimestamp' => new \DateTime(), // cab be future date
            'billReference' => (string) $date->timestamp,
            'description' => 'Test Order',
            'amount' => '20',
            'language' => 'EN',
            'currency' => 'PKR',
            'transactionId' => $date->timestamp,
            'extra' => [
                'field_1' => 'value_1',
                'field_2' => 'value_2',
                'field_3' => 'value_3',
                'field_4' => 'value_4',
                'field_5' => 'value_5'
            ],
            'returnUrl' => 'https://exmpale.com/return',
        ]
    ];
    
    $response = $gateway->purchase($parameters)->send();
    
    // var_dump($response->getData());
    
    if ($response->isSuccessful() && $response instanceof RedirectIn) {
        // do the redirect here you can do it auto using the following return statement
        // return $response->getRedirectResponse();
    } else {
    // handle failed response
    }
} catch (\Throwable $exception) {
    var_dump($exception);
}

```
***Note:*** Check this class for more redirect logic [PurchaseResponse.php](src%2FMessage%2FPurchaseResponse.php)

### Complete Purchase

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Jazzcash');

// initialize with array
$gateway->initialize([
    'merchantId' => 'your-merchant-id',
    'password' => 'your-password',
    'secretKey' => 'your-secret-key',
]);

try {
    $response = $gateway->completePurchase()->send();
    
    if (!$response->isSuccessful()) {
        // handle failed logic
    }
    
    // always good option to cross-check the response data with gateway
    $inquiryResponse = $gateway->fetchTransaction([
        'transactionReference' => $response->getData()['pp_TxnRefNo'],
    ])
    
    // see next step for more details
    
} catch (\Throwable $exception) {
    var_dump($exception);
}

$response = $gateway->completePurchase()



```

### Inquiry Request
```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Jazzcash');

// initialize with array
$gateway->initialize([
    'merchantId' => 'your-merchant-id',
    'password' => 'your-password',
    'secretKey' => 'your-secret-key',
]);

try {
    $parameters = [
        'transactionReference' => '<pp_TxnRefNo>',
    ];
    
    $response = $gateway->fetchTransaction($parameters)->send();
    
    // var_dump($response->getData());
    
    if ($response->isSuccessful()) {
        if ($response->getData()['pp_Status'] === 'Complete') {
            // the transaction is success
        } else {
            
        }
     // handle success response
    } else {
    // handle failed response
    }
} catch (\Throwable $exception) {
    var_dump($exception);
}

```
***Note:*** Check [FetchTransactionResponse.php](src%2FMessage%2FFetchTransactionResponse.php) for more response statuses

**NOTE:** You can check the tests Mock for sample response data.

## License

This package is released under the [MIT License](https://opensource.org/licenses/MIT). See the [LICENSE](LICENSE) file for details.

## Contact
You can reach me here [moeen.basra@gamil.com](mailto:moeen.basra@gamil.com)
