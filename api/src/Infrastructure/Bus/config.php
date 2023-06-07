<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Contracts\Bus\Command\CommandBusInterface;
use App\Contracts\Bus\Event\EventBusInterface;
use App\Contracts\Bus\Query\QueryBusInterface;
use App\Infrastructure\Bus\Symfony\Command\CommandBus;
use App\Infrastructure\Bus\Symfony\Event\EventBus;
use App\Infrastructure\Bus\Symfony\Middleware\ValidationMiddleware;
use App\Infrastructure\Bus\Symfony\Query\QueryBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(ValidationMiddleware::class);

    $services->set(CommandBus::class);
    $services->alias(CommandBusInterface::class, CommandBus::class);

    $services->set(EventBus::class);
    $services->alias(EventBusInterface::class, EventBus::class);

    $services->set(QueryBus::class);
    $services->alias(QueryBusInterface::class, QueryBus::class);
};
