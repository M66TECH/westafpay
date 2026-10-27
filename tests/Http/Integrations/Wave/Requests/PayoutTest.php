<?php

use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\CreatePayout;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\GetPayout;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\GetPayoutBatch;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\PayoutBatch;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\ReversePayout;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\SearchPayout;
use Saloon\Enums\Method;

it('creates a payout', function () {
    $request = new CreatePayout([
        'currency' => 'XOF',
        'receive_amount' => '500',
        'name' => 'Fatou Ndiaye',
        'mobile' => '+221555110219',
    ]);

    expect($request->resolveEndpoint())->toBe('/v1/payout')
        ->and($request->getMethod())->toBe(Method::POST);
});

it('gets a payout by id', function () {
    $request = new GetPayout('pt-185sewgm8100t');

    expect($request->resolveEndpoint())->toBe('/v1/payout/pt-185sewgm8100t')
        ->and($request->getMethod())->toBe(Method::GET);
});

it('searches payouts using the plural endpoint', function () {
    $request = new SearchPayout('FAH.4827.1734');

    expect($request->resolveEndpoint())->toBe('/v1/payouts/search')
        ->and($request->getMethod())->toBe(Method::GET)
        ->and($request->query()->all())->toMatchArray(['client_reference' => 'FAH.4827.1734']);
});

it('creates a payout batch', function () {
    $request = new PayoutBatch(['payouts' => []]);

    expect($request->resolveEndpoint())->toBe('/v1/payout-batch')
        ->and($request->getMethod())->toBe(Method::POST);
});

it('gets a payout batch by id', function () {
    $request = new GetPayoutBatch('pb-185skxq8g1006');

    expect($request->resolveEndpoint())->toBe('/v1/payout-batch/pb-185skxq8g1006')
        ->and($request->getMethod())->toBe(Method::GET);
});

it('reverses a payout using the reverse endpoint', function () {
    $request = new ReversePayout('pt-185sewgm8100t');

    expect($request->resolveEndpoint())->toBe('/v1/payout/pt-185sewgm8100t/reverse')
        ->and($request->getMethod())->toBe(Method::POST);
});

it('does not expose a non existing reserve endpoint', function () {
    expect(class_exists('Laravelsn\\Westafpay\\Http\\Integrations\\Wave\\Requests\\Payout\\ReservePayout'))->toBeFalse();
});
