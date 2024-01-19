<?php

declare(strict_types=1);

namespace App\Infrastructure\Flusher;

use App\Contracts\Flusher\FlusherInterface;
use App\Infrastructure\Flusher\All\AllFlusher;
use App\Infrastructure\Flusher\Doctrine\DoctrineFlusher;
use App\Infrastructure\Flusher\DomainEvent\DomainEventDispatcherFlusher;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(DomainEventDispatcherFlusher::class)->tag('flusher', ['priority' => -910]);
    $services->set(DoctrineFlusher::class)->tag('flusher', ['priority' => -915]);
    $services->set(AllFlusher::class)->args([tagged_iterator('flusher')]);

    $services->alias(FlusherInterface::class, AllFlusher::class);
};
