## Summary

While comparing every request in this package with the official [Wave documentation](https://docs.wave.com),
I found several endpoints that would fail at runtime, and noticed that the published config was never
registered. This PR fixes those issues, makes the package installable as documented, and adds a test
suite so regressions are caught.

## Fixed

| Problem | Before | After |
| --- | --- | --- |
| Config never published | `WestafpayServiceProvider` only did a `bind`, so `config('westafpay.wave.base_url')` was `null` in a host app | Uses `spatie/laravel-package-tools`; `vendor:publish --tag=westafpay-config` now works |
| Invalid base URL | `https://api.wave.com.` (trailing dot) | `https://api.wave.com` |
| Wrong Checkout endpoints (6 requests) | `/v1/checkouts/sessions` | `/v1/checkout/sessions` |
| Wrong Payout search endpoint | `/v1/payout/search` | `/v1/payouts/search` |
| Non-existent endpoint | `reservePayout()` → `POST /v1/payout/:id/reserve` | `reversePayout()` → `POST /v1/payout/:id/reverse` |
| Namespace typo | `DataTransfertObjects` | `DataTransferObjects` |
| Command never registered | `WestafpayCommand` unused | Registered through the package tools |

The Checkout, Payout search and payout "reserve" endpoints were verified against the
[Checkout API](https://docs.wave.com/checkout), [Payout API](https://docs.wave.com/payout) and
[Balance API](https://docs.wave.com/balance-api) documentation.

## Added

- **Request signing**: when `WAVE_SIGNING_SECRET` is configured, every outgoing request gets a
  `Wave-Signature: t={timestamp},v1={hmac}` header (HMAC-SHA256 of `timestamp + raw body`), as described
  by Wave. GET requests are signed with the timestamp only. See `WaveConnector::signature()`.
- **Transaction pagination**: `paginateTransactions()` lazily walks every page using
  `page_info.end_cursor` as the `after` cursor. `getTransactions()` now also accepts `first`, and
  `include_subaccounts` is sent as `false` when explicitly disabled.
- **Timeouts**: 10s connect / 30s request timeout on the connector.
- **Tests**: 39 Pest tests (84 assertions) covering:
  - the connector (headers, bearer token, signing, pagination cursor),
  - the client (balance, transactions, checkout, payout, error handling),
  - every Wave request (endpoint, HTTP method, query parameters).
- **Docs**: a full `README.md`, a new `CONTRIBUTING.md`, an updated `CHANGELOG.md`, and `TODO.md`
  items checked off.
- **Windows helper**: `scripts/php84.ps1` runs Pint, PHPStan and Pest with a PHP 8.4 binary.

## Breaking changes

- `reservePayout()` was removed in favor of `reversePayout()` — the previous endpoint does not exist on
  Wave and could never work.
- The `DataTransfertObjects` namespace was renamed to `DataTransferObjects`.
- `WaveProvider::getTransactions()` gained an optional, backwards-compatible `$first` parameter.

## How to test

```bash
composer install
composer format     # Pint
composer analyse    # PHPStan (level 5)
composer test       # Pest
```

On Windows, with PHP 8.4 not in your `PATH`:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/php84.ps1
```

## Checklist

- [x] `composer format` passes (Pint)
- [x] `composer analyse` passes (PHPStan, no errors)
- [x] `composer test` passes (39 tests)
- [x] No secrets or credentials committed
- [x] Documentation updated
