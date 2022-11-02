<?php

declare(strict_types=1);

namespace App\Http;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Http\\', '.')->exclude([
        './config.php',
        './Response',
        './Test',
    ])->tag('controller.service_arguments');
};
