<?php

declare(strict_types=1);

namespace App\Shared;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\', '.')->exclude([
        './config.php',
        './*/Test',
        './Doctrine',
        './Flusher',
        './ValueObject',
        './Aggregate',
        './FeatureToggle',
        './Notifier',
        './Assert.php',
    ]);
};
