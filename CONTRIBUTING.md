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
