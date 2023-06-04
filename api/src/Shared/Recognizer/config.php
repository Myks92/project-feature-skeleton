<?php

declare(strict_types=1);

namespace App\Shared\Recognizer;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\Recognizer\\', '.')->exclude([
        './config.php',
        './*/Test',
        './*/Exception',
    ]);
};
