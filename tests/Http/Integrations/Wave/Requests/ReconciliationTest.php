<?php

use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Reconciliation\Balance;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Reconciliation\Refund;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Reconciliation\Transaction;
use Saloon\Enums\Method;

it('requests the balance endpoint', function () {
    $request = new Balance;

    expect($request->resolveEndpoint())->toBe('/v1/balance')
        ->and($request->getMethod())->toBe(Method::GET);
});

it('requests the transactions endpoint', function () {
    $request = new Transaction;

    expect($request->resolveEndpoint())->toBe('/v1/transactions')
        ->and($request->getMethod())->toBe(Method::GET)
        ->and($request->query()->all())->toBe([]);
});

it('passes the transactions query parameters', function () {
    $request = new Transaction('2022-11-07', true, 100);

    expect($request->query()->all())->toMatchArray([
        'date' => '2022-11-07',
        'include_subaccounts' => 'true',
        'first' => 100,
    ]);
});

it('always sends include_subaccounts when explicitly disabled', function () {
    $request = new Transaction(includeSubaccounts: false);

    expect($request->query()->all())->toMatchArray(['include_subaccounts' => 'false']);
});

it('refunds a transaction', function () {
    $request = new Refund('T_V3TFOUE7VU');

    expect($request->resolveEndpoint())->toBe('/v1/transactions/T_V3TFOUE7VU/refund')
        ->and($request->getMethod())->toBe(Method::POST);
});
