# Westafpay

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravelsn/westafpay.svg?style=flat-square)](https://packagist.org/packages/laravelsn/westafpay)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/laravelsn/westafpay/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/laravelsn/westafpay/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/laravelsn/westafpay/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/laravelsn/westafpay/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/laravelsn/westafpay.svg?style=flat-square)](https://packagist.org/packages/laravelsn/westafpay)

Westafpay vous permet d'interagir facilement avec les fournisseurs de paiement d'Afrique de l'Ouest depuis votre application Laravel.

Le package est construit sur [Saloon](https://docs.saloon.dev) et prend actuellement en charge les API de [Wave](https://docs.wave.com) :

- API Checkout
- API Payout
- API Balance & Reconciliation
- API Merchant
- Signature des requêtes (`Wave-Signature`)

## Installation

Vous pouvez installer le package via Composer :

```bash
composer require laravelsn/westafpay
```

Vous pouvez publier le fichier de configuration avec :

```bash
php artisan vendor:publish --tag="westafpay-config"
```

Voici le contenu du fichier de configuration publié (`config/westafpay.php`) :

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

Ajoutez vos identifiants dans le fichier `.env` :

```dotenv
WAVE_API_KEY=wave_sn_prod_...
# Optionnel : requis uniquement si la signature des requêtes est activée sur votre clé API
WAVE_SIGNING_SECRET=wave_sn_AKS_...
```

## Utilisation

```php
use Laravelsn\Westafpay\Facades\Westafpay;

// Balance & réconciliation
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

### Pagination des transactions

`getTransactions()` renvoie uniquement la première page brute. Utilisez `paginateTransactions()`
pour parcourir paresseusement toutes les pages, en utilisant `page_info.end_cursor` comme curseur `after` :

```php
foreach (Westafpay::paginateTransactions(date: '2022-11-07') as $response) {
    foreach ($response->json('items') as $transaction) {
        // ...
    }
}
```

### Signature des requêtes

Lorsque `WAVE_SIGNING_SECRET` est défini, chaque requête est automatiquement signée avec un en-tête
`Wave-Signature: t={timestamp},v1={hmac}`, comme décrit dans la
[documentation de Wave](https://docs.wave.com/balance-api#request-signing).

## Tests

```bash
composer test
composer analyse
composer format
```

## Changelog

Veuillez consulter le [CHANGELOG](CHANGELOG.md) pour plus d'informations sur les changements récents.

## Contribution

Veuillez consulter le [CONTRIBUTING](CONTRIBUTING.md) pour plus de détails.

## Vulnérabilités de sécurité

Veuillez consulter [notre politique de sécurité](../../security/policy) pour savoir comment signaler
une vulnérabilité.

## Crédits

- [Laravelsn](https://github.com/Laravelsn)
- [Tous les contributeurs](../../contributors)

## Licence

La licence MIT (MIT). Veuillez consulter le [fichier de licence](LICENSE.md) pour plus d'informations.
