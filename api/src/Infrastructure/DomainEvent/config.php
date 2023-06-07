<?php

declare(strict_types=1);

namespace App\Infrastructure\DomainEvent;

use App\Contracts\DomainEvent\EventDispatcherInterface;
use App\Infrastructure\DomainEvent\Psr\PsrEventDispatcher;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(PsrEventDispatcher::class);
    $services->alias(EventDispatcherInterface::class, PsrEventDispatcher::class);
};
