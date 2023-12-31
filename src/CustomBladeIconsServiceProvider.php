<?php

namespace Jmal\CustomBladeIcons;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CustomBladeIconsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('custom-blade-icons')
            ->hasConfigFile();
    }
}
