<?php

declare(strict_types=1);

namespace App\Shared\DomainEvent;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\DomainEvent\\', '.')->exclude([
        './config.php',
        './Test',
    ]);
};
