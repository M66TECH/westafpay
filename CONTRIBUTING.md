# Contributing to Westafpay

Thanks for considering contributing to Westafpay. This document explains how to get started.

## Requirements

- PHP **8.4** or higher
- [Composer](https://getcomposer.org)

## Local setup

```bash
git clone https://github.com/laravel-sn/westafpay.git
cd westafpay
composer install
```

## Useful commands

```bash
composer test         # Run the Pest test suite
composer analyse      # Run PHPStan (larastan)
composer format       # Run Pint
```

### Windows

The package requires PHP 8.4, but Windows users often have an older PHP in their `PATH`.
`scripts/php84.ps1` runs the same checks with a dedicated PHP 8.4 binary:

```powershell
# Run Pint, PHPStan and Pest
powershell -ExecutionPolicy Bypass -File scripts/php84.ps1

# Run a single check
powershell -ExecutionPolicy Bypass -File scripts/php84.ps1 -Task test
powershell -ExecutionPolicy Bypass -File scripts/php84.ps1 -Task analyse
powershell -ExecutionPolicy Bypass -File scripts/php84.ps1 -Task format

# Use a specific PHP 8.4 binary
$env:PHP84_PATH = 'C:\php84\php.exe'
powershell -ExecutionPolicy Bypass -File scripts/php84.ps1
```

The binary is looked up in this order: `-Php`, `$env:PHP84_PATH`, `./.php84/php.exe`, then the default temp path.

Please make sure all three commands pass before opening a pull request.

## Workflow

1. Fork the repository.
2. Create a branch from `main`: `git checkout -b fix/my-fix`.
3. Make your change, add tests covering it and update the docs if needed.
4. Run `composer format`, `composer analyse` and `composer test`.
5. Commit using a clear message, e.g. `fix: use singular checkout endpoint`.
6. Push your branch and open a pull request against `main`.

## Where to contribute

Look at [TODO.md](TODO.md) for known missing features and at the open issues. Some good starting points:

- Adding tests for endpoints that are not covered yet.
- Implementing a new Wave API (e.g. webhooks).
- Improving the documentation.
- Supporting another West African payment provider.

## Adding a new Wave endpoint

Requests live in `src/Http/Integrations/Wave/Requests/<Api>/`. Each request is a Saloon
`Request` that only needs a `resolveEndpoint()` (and optionally `defaultQuery()` /
`defaultBody()`). Then expose it from `WaveClient`, `WaveProvider` and add a test in
`tests/Http/Integrations/Wave/Requests`.

Always double-check the endpoint against the [official Wave documentation](https://docs.wave.com).

## Coding style

- Follow the existing conventions (Pint / Laravel preset).
- Do not use debugging helpers such as `dd`, `dump` or `ray` (enforced by an arch test).
- Keep pull requests focused: one topic per pull request.
