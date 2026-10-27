<?php

use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\CreateCheckout;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\ExpireCheckout;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\GetCheckout;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\GetCheckoutByTransactionId;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\RefundCheckout;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\SearchForCheckout;
use Saloon\Enums\Method;

it('creates a checkout session', function () {
    $request = new CreateCheckout(['amount' => '1000', 'currency' => 'XOF']);

    expect($request->resolveEndpoint())->toBe('/v1/checkout/sessions')
        ->and($request->getMethod())->toBe(Method::POST);
});

it('gets a checkout session by id', function () {
    $request = new GetCheckout('cos-1b01sghpg100j');

    expect($request->resolveEndpoint())->toBe('/v1/checkout/sessions/cos-1b01sghpg100j')
        ->and($request->getMethod())->toBe(Method::GET);
});

it('gets a checkout session by transaction id', function () {
    $request = new GetCheckoutByTransactionId('TCN4Y4ZC3FM');

    expect($request->resolveEndpoint())->toBe('/v1/checkout/sessions')
        ->and($request->getMethod())->toBe(Method::GET)
        ->and($request->query()->all())->toMatchArray(['transaction_id' => 'TCN4Y4ZC3FM']);
});

it('searches for a checkout session', function () {
    $request = new SearchForCheckout('order-123');

    expect($request->resolveEndpoint())->toBe('/v1/checkout/sessions/search')
        ->and($request->getMethod())->toBe(Method::GET)
        ->and($request->query()->all())->toMatchArray(['client_reference' => 'order-123']);
});

it('refunds a checkout session', function () {
    $request = new RefundCheckout('TCN4Y4ZC3FM');

    expect($request->resolveEndpoint())->toBe('/v1/checkout/sessions/TCN4Y4ZC3FM/refund')
        ->and($request->getMethod())->toBe(Method::POST);
});

it('expires a checkout session', function () {
    $request = new ExpireCheckout('cos-1b01sghpg100j');

    expect($request->resolveEndpoint())->toBe('/v1/checkout/sessions/cos-1b01sghpg100j/expire')
        ->and($request->getMethod())->toBe(Method::POST);
});
