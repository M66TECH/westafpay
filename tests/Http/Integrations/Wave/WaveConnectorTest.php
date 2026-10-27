<?php

use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout\CreateCheckout;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Reconciliation\Balance;
use Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Reconciliation\Transaction;
use Laravelsn\Westafpay\Http\Integrations\Wave\WaveConnector;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('resolves the wave base url from the config', function () {
    config()->set('westafpay.wave.base_url', 'https://api.wave.com');

    expect((new WaveConnector)->resolveBaseUrl())
        ->toBe('https://api.wave.com');
});

it('sends the api key as a bearer token', function () {
    config()->set('westafpay.wave.api_key', 'wave_sn_test_key');

    $connector = new WaveConnector;
    $pendingRequest = $connector->createPendingRequest(new Balance);

    expect($pendingRequest->headers()->all())
        ->toMatchArray([
            'Authorization' => 'Bearer wave_sn_test_key',
            'Accept' => 'application/json',
        ]);
});

it('does not sign requests when no signing secret is configured', function () {
    config()->set('westafpay.wave.signing_secret', null);

    $connector = new WaveConnector;
    $pendingRequest = $connector->createPendingRequest(new Balance);

    expect($pendingRequest->headers()->all())->not->toHaveKey('Wave-Signature');
});

it('signs requests with an HMAC of timestamp and body', function () {
    config()->set('westafpay.wave.signing_secret', 'wave_sn_AKS_secret');

    $connector = new WaveConnector;
    $pendingRequest = $connector->createPendingRequest(
        new CreateCheckout([
            'amount' => '1000',
            'currency' => 'XOF',
        ])
    );

    $signature = $pendingRequest->headers()->get('Wave-Signature');

    expect($signature)->toMatch('/^t=\d+,v1=[0-9a-f]{64}$/');

    [$timestamp, $hash] = sscanf($signature, 't=%d,v1=%s');

    expect($hash)->toBe(hash_hmac('sha256', $timestamp.'{"amount":"1000","currency":"XOF"}', 'wave_sn_AKS_secret'));
});

it('signs GET requests using only the timestamp', function () {
    config()->set('westafpay.wave.signing_secret', 'wave_sn_AKS_secret');

    $connector = new WaveConnector;
    $pendingRequest = $connector->createPendingRequest(new Balance);

    [$timestamp, $hash] = sscanf($pendingRequest->headers()->get('Wave-Signature'), 't=%d,v1=%s');

    expect($hash)->toBe(hash_hmac('sha256', (string) $timestamp, 'wave_sn_AKS_secret'));
});

it('paginates transactions using the page_info cursor', function () {
    $firstPage = [
        'page_info' => ['start_cursor' => null, 'end_cursor' => 'cursor-1', 'has_next_page' => true],
        'date' => '2022-11-07',
        'items' => [
            ['timestamp' => '2022-11-07T14:41:15Z', 'transaction_id' => 'T_1', 'amount' => '99', 'fee' => '1', 'currency' => 'XOF'],
        ],
    ];

    $secondPage = [
        'page_info' => ['start_cursor' => 'cursor-1', 'end_cursor' => 'cursor-2', 'has_next_page' => false],
        'date' => '2022-11-07',
        'items' => [
            ['timestamp' => '2022-11-07T14:42:06Z', 'transaction_id' => 'T_2', 'amount' => '99', 'fee' => '1', 'currency' => 'XOF'],
        ],
    ];

    $mockClient = new MockClient([
        MockResponse::make($firstPage),
        MockResponse::make($secondPage),
    ]);

    $connector = waveConnector($mockClient);

    iterator_to_array($connector->paginate(new Transaction('2022-11-07')));

    $mockClient->assertSentCount(2);

    $requests = $mockClient->getRecordedResponses();

    expect($requests[0]->getRequest()->query()->all())->toMatchArray(['date' => '2022-11-07'])
        ->and($requests[1]->getRequest()->query()->all())->toMatchArray(['date' => '2022-11-07', 'after' => 'cursor-1'])
        ->and($requests[0]->getRequest()->getMethod())->toBe(Method::GET);
});
