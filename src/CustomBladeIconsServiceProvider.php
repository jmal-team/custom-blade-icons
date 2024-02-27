<?php

namespace Jmal\CustomBladeIcons;

use BladeUI\Icons\Factory;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Contracts\Container\Container;
use RecursiveDirectoryIterator;

class CustomBladeIconsServiceProvider extends PackageServiceProvider
{
    private function autoImportSvgFolders(string $folder, Factory $factory)
    {
        //iterate through the folder recursively
        $dirIterator = new RecursiveDirectoryIterator($folder);

        /** @var \RecursiveDirectoryIterator | \RecursiveIteratorIterator $it */
        $it = new \RecursiveIteratorIterator($dirIterator);
        $folders = [];

        while ($it->valid()) {
            if (
                $it->isFile() &&
                !$it->isDot() &&
                $it->current()->getExtension() === 'svg' &&
                $it->isReadable()
            ) {
                $fileName = str($it->getPathname())->after($folder . '/')->before('/');
                if (!str($fileName)->endsWith('.svg')) {
                    $folders[] = $fileName->value();
                }
            }
            $it->next();
        }

        $folders = array_unique($folders);

        foreach ($folders as $value) {
            $factory->add(
                $value,
                array_merge(['path' => $folder . '/' . $value], [

                    'prefix' => $value,

                    'fallback' => '',

                    'class' => '',

                    'attributes' => [
                        // 'width' => 50,
                        // 'height' => 50,
                    ],
                ])
            );
        }
    }

    public function register()
    {
        parent::register();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config');

            $svgDirectory = $config->get('custom-blade-icons.svgs_default_path');

            $factory->add('custom', array_merge(['path' => $svgDirectory], $config->get('custom-blade-icons.custom', [])));

            $this->autoImportSvgFolders($svgDirectory, $factory);
        });
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('custom-blade-icons')
            ->hasConfigFile('custom-blade-icons');
    }
}
