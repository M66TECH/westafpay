<?php

use Illuminate\Support\ServiceProvider;
use Laravelsn\Westafpay\Westafpay;
use Laravelsn\Westafpay\WestafpayServiceProvider;
use Spatie\LaravelPackageTools\PackageServiceProvider;

it('registers the package service provider as a spatie package', function () {
    $provider = app()->getProvider(WestafpayServiceProvider::class);

    expect($provider)->toBeInstanceOf(PackageServiceProvider::class)
        ->and($provider)->toBeInstanceOf(ServiceProvider::class);
});

it('resolves the westafpay manager from the container', function () {
    expect(app('westafpay'))->toBeInstanceOf(Westafpay::class);
});

it('uses wave as the default driver', function () {
    expect(app('westafpay')->getDefaultDriver())->toBe('wave');
});

it('merges the default config', function () {
    expect(config('westafpay.wave.base_url'))->toBe('https://api.wave.com')
        ->and(config('westafpay.wave.api_key'))->toBeNull()
        ->and(config('westafpay.wave.signing_secret'))->toBeNull();
});

it('has a valid base url without trailing dot', function () {
    expect(config('westafpay.wave.base_url'))->not->toEndWith('.');
});

it('registers the westafpay artisan command', function () {
    $this->artisan('westafpay')->assertSuccessful();
});

it('publishes the config file under the westafpay-config tag', function () {
    expect(ServiceProvider::publishableGroups())->toContain('westafpay-config');
});
