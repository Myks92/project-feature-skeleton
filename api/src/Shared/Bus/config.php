<?php

declare(strict_types=1);

namespace App\Shared\Bus;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\Bus\\', '.')->exclude([
        './config.php',
        './Test',
    ]);
};
