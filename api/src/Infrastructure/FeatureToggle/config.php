<?php

declare(strict_types=1);

namespace App\Infrastructure\FeatureToggle;

use App\Contracts\FeatureToggle\FeatureContextInterface;
use App\Contracts\FeatureToggle\FeatureFlagInterface;
use App\Contracts\FeatureToggle\FeatureSwitcherInterface;
use App\Infrastructure\FeatureToggle\Memory\MemoryFeatures;
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

    $services->set(MemoryFeatures::class)
        ->arg('$features', param('feature_toggle.features'));

    $services->alias(FeatureFlagInterface::class, MemoryFeatures::class);
    $services->alias(FeatureContextInterface::class, MemoryFeatures::class);
    $services->alias(FeatureSwitcherInterface::class, MemoryFeatures::class);
};
