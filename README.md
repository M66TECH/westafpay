# Westafpay

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravelsn/westafpay.svg?style=flat-square)](https://packagist.org/packages/laravelsn/westafpay)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/laravelsn/westafpay/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/laravelsn/westafpay/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/laravelsn/westafpay/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/laravelsn/westafpay/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/laravelsn/westafpay.svg?style=flat-square)](https://packagist.org/packages/laravelsn/westafpay)

Westafpay lets you interact easily with payment providers in West Africa from your Laravel application.

The package is built on top of [Saloon](https://docs.saloon.dev) and currently supports the [Wave](https://docs.wave.com) APIs:

- Checkout API
- Payout API
- Balance & Reconciliation API
- Merchant API
- Request signing (`Wave-Signature`)

## Installation

You can install the package via composer:

```bash
composer require laravelsn/westafpay
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="westafpay-config"
```

This is the contents of the published config file (`config/westafpay.php`):

```php
return [
    'wave' => [
        'base_url' => env('WAVE_BASE_URL', 'https://api.wave.com'),
        'api_key' => env('WAVE_API_KEY'),
        'signing_secret' => env('WAVE_SIGNING_SECRET'),
        'webhook_secret' => env('WAVE_WEBHOOK_SECRET'),
    ],
];
```

Add your credentials to `.env`:

```dotenv
WAVE_API_KEY=wave_sn_prod_...
# Optional: only required when request signing is enabled on your API key
WAVE_SIGNING_SECRET=wave_sn_AKS_...
```

## Usage

```php
use Laravelsn\Westafpay\Facades\Westafpay;

// Balance & reconciliation
Westafpay::getBalance();
Westafpay::getBalance(include_subaccounts: true);
Westafpay::getTransactions(date: '2022-11-07');
Westafpay::refund('T_VZSWJF5MMQ');

// Checkout
Westafpay::createCheckout([
    'amount' => '1000',
    'currency' => 'XOF',
    'success_url' => 'https://example.com/success',
    'error_url' => 'https://example.com/error',
]);
Westafpay::getCheckout('cos-18qq25rgr100a');
Westafpay::getCheckoutByTransactionId('T_VZSWJF5MMQ');
Westafpay::searchForCheckout('order-123');
Westafpay::refundCheckout('T_VZSWJF5MMQ');
Westafpay::expireCheckout('cos-18qq25rgr100a');

// Payout
Westafpay::createPayout([
    'currency' => 'XOF',
    'receive_amount' => '500',
    'name' => 'Fatou Ndiaye',
    'mobile' => '+221555110219',
]);
Westafpay::getPayout('pt-185sewgm8100t');
Westafpay::searchPayout('FAH.4827.1734');
Westafpay::createPayoutBatch(['payouts' => [/* ... */]]);
Westafpay::getPayoutBatch('pb-185skxq8g1006');
Westafpay::reversePayout('pt-185sewgm8100t');
```

### Paginating transactions

`getTransactions()` returns the raw first page. Use `paginateTransactions()` to lazily walk every page,
using Wave's `page_info.end_cursor` as the `after` cursor:

```php
foreach (Westafpay::paginateTransactions(date: '2022-11-07') as $response) {
    foreach ($response->json('items') as $transaction) {
        // ...
    }
}
```

### Request signing

When `WAVE_SIGNING_SECRET` is set, every request is automatically signed with a fresh
`Wave-Signature: t={timestamp},v1={hmac}` header, as described in the
[Wave documentation](https://docs.wave.com/balance-api#request-signing).

## Testing

```bash
composer test
composer analyse
composer format
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Laravelsn](https://github.com/Laravelsn)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
