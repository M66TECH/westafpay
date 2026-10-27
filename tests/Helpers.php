<?php

use Laravelsn\Westafpay\Http\Integrations\Wave\WaveConnector;
use Saloon\Http\Faking\MockClient;

function waveConnector(MockClient $mockClient): WaveConnector
{
    $connector = new WaveConnector;

    $connector->withMockClient($mockClient);

    return $connector;
}
