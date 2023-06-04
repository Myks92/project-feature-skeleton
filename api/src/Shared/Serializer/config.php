<?php

declare(strict_types=1);

namespace App\Shared\Serializer;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\Serializer\\', '.')->exclude([
        './config.php',
        './Test',
    ]);
};
