<?php

namespace Jmal\CustomBladeIcons;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CustomBladeIconsServiceProvider extends PackageServiceProvider
{
    public function register()
    {
        parent::register();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config');

            $factory->add('custom', array_merge(['path' => resource_path('svg')], $config->get('custom-blade-icons.custom', [])));
        });
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('custom-blade-icons')
            ->hasConfigFile('custom-blade-icons');
    }
}
