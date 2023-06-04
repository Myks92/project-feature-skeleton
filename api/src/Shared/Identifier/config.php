<?php

declare(strict_types=1);

namespace App\Shared\Identifier;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\Identifier\\', '.')->exclude([
        './config.php',
        './Test',
    ]);
};
