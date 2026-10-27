# Changelog

All notable changes to `westafpay` will be documented in this file.

## Unreleased

### Fixed

- Publish the config file and register the `westafpay` command through `spatie/laravel-package-tools`.
- Fix the default Wave base URL (`https://api.wave.com.` had a trailing dot).
- Use the correct singular Wave Checkout endpoints (`/v1/checkout/sessions` instead of `/v1/checkouts/sessions`).
- Use the correct Wave Payout search endpoint (`/v1/payouts/search` instead of `/v1/payout/search`).
- Replace the non existing `reservePayout()` (there is no `reserve` endpoint on Wave) with `reversePayout()` (`POST /v1/payout/:id/reverse`).
- Rename the `DataTransfertObjects` namespace to `DataTransferObjects`.

### Added

- Request signing: automatically send a `Wave-Signature` header when `WAVE_SIGNING_SECRET` is set.
- Paginated transactions through `paginateTransactions()`, and `first` support on `getTransactions()`.
- Optional `signing_secret` and `webhook_secret` config keys.
- A test suite covering the connector, the client and the Wave requests.
- `CONTRIBUTING.md` and a more complete `README.md`.
