<?php

use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\CreateCheckout;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Payout\ReversePayout;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Reconciliation\Balance;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Reconciliation\Transaction;
use Laravelsn\Westafpay\Providers\Wave\WaveClient;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

function clientWith(MockClient $mockClient): WaveClient
{
    $client = new WaveClient;

    (fn () => $this->connector = waveConnector($mockClient))->call($client);

    return $client;
}

it('returns the balance payload', function () {
    $client = clientWith(new MockClient([
        Balance::class => MockResponse::make(['amount' => '10245', 'currency' => 'XOF']),
    ]));

    expect($client->balance())->toBe(['amount' => '10245', 'currency' => 'XOF']);
});

it('forwards include_subaccounts to the balance request', function () {
    $mockClient = new MockClient([
        Balance::class => MockResponse::make(['amount' => '10245', 'currency' => 'XOF']),
    ]);

    clientWith($mockClient)->balance(true);

    expect($mockClient->getLastPendingRequest()->query()->all())
        ->toMatchArray(['include_subaccounts' => 'true']);
});

it('returns the transactions payload', function () {
    $payload = [
        'page_info' => ['start_cursor' => null, 'end_cursor' => 'abc', 'has_next_page' => false],
        'date' => '2022-11-07',
        'items' => [],
    ];

    $client = clientWith(new MockClient([
        Transaction::class => MockResponse::make($payload),
    ]));

    expect($client->transactions('2022-11-07'))->toBe($payload);
});

it('returns the created checkout payload', function () {
    $payload = ['id' => 'cos-1b01sghpg100j', 'checkout_status' => 'open'];

    $client = clientWith(new MockClient([
        CreateCheckout::class => MockResponse::make($payload),
    ]));

    expect($client->createCheckout(['amount' => '100', 'currency' => 'XOF']))->toBe($payload);
});

it('returns an empty payload when reversing a payout', function () {
    $client = clientWith(new MockClient([
        ReversePayout::class => MockResponse::make('', 200),
    ]));

    expect($client->reversePayout('pt-185sewgm8100t'))->toBe([]);
});

it('throws when the api answers with an error', function () {
    $client = clientWith(new MockClient([
        Balance::class => MockResponse::make(['error' => 'invalid-auth'], 401),
    ]));

    $client->balance();
})->throws(RequestException::class);
