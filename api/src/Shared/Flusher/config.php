<?php

declare(strict_types=1);

namespace App\Shared\Flusher;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\Flusher\\', '.')->exclude([
        './config.php',
        './*/Test',
    ]);

    $services->get(DomainEventDispatcherFlusher::class)->tag('flusher', ['priority' => -910]);
    $services->get(DoctrineFlusher::class)->tag('flusher', ['priority' => -915]);
    $services->set(FlusherInterface::class, FlushersFlusher::class)->args([tagged_iterator('flusher')]);
};
