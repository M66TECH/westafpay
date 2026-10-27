<?php

namespace Laravelsn\Westafpay;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WestafpayServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('westafpay')
            ->hasConfigFile()
            ->hasCommand(Commands\WestafpayCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->bind('westafpay', Westafpay::class);
    }
}
