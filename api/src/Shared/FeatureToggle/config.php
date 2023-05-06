<?php

declare(strict_types=1);

namespace App\Shared\FeatureToggle;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $configurator): void {
    $configurator->parameters()
        ->set('feature_toggle.features', []);

    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\FeatureToggle\\', '.')->exclude([
        './config.php',
    ]);

    $services->get(Features::class)
        ->arg('$features', param('feature_toggle.features'));
};
