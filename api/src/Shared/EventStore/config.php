<?php

declare(strict_types=1);

namespace App\Shared\EventStore;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\EventStore\\', '.')->exclude([
        './config.php',
        './Test',
        './Event.php',
    ]);
};
