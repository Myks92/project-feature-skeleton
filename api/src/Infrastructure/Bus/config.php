<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Contracts\Bus\Command\CommandBusInterface;
use App\Contracts\Bus\Event\EventBusInterface;
use App\Contracts\Bus\Query\QueryBusInterface;
use App\Infrastructure\Bus\Command\Attribute\CommandHandler;
use App\Infrastructure\Bus\Event\Attribute\EventHandler;
use App\Infrastructure\Bus\Query\Attribute\QueryHandler;
use App\Infrastructure\Bus\Symfony\Command\CommandBus;
use App\Infrastructure\Bus\Symfony\Event\EventBus;
use App\Infrastructure\Bus\Symfony\Middleware\ValidationMiddleware;
use App\Infrastructure\Bus\Symfony\Query\QueryBus;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator, ContainerBuilder $container): void {
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

    $container->registerAttributeForAutoconfiguration(
        CommandHandler::class,
        static function (
            ChildDefinition $definition,
            CommandHandler $attribute,
            \Reflector $reflector,
        ): void {
            if (!$reflector instanceof \ReflectionClass) {
                return;
            }
            $method = '__invoke';
            $reflectorMethod = $reflector->getMethod($method);
            /** @var \ReflectionNamedType|null $reflectorMethodType */
            $reflectorMethodType = $reflectorMethod->getParameters()[0]->getType();
            $definition->addTag('messenger.message_handler', [
                'bus' => 'command.bus',
                'method' => $method,
                'handles' => $reflectorMethodType?->getName(),
                'from_transport' => $attribute->transport,
            ]);
        },
    );

    $container->registerAttributeForAutoconfiguration(
        EventHandler::class,
        static function (
            ChildDefinition $definition,
            EventHandler $attribute,
            \ReflectionClass|\ReflectionMethod|\Reflector $reflector,
        ): void {
            $method = ($reflector instanceof \ReflectionMethod) ? $reflector->getName() : '__invoke';
            $definition->addTag('messenger.message_handler', [
                'bus' => 'event.bus',
                'method' => $method,
                'handles' => $attribute->event,
                'from_transport' => $attribute->transport,
                'priority' => $attribute->priority,
            ]);
        },
    );

    $container->registerAttributeForAutoconfiguration(
        QueryHandler::class,
        static function (
            ChildDefinition $definition,
            QueryHandler $attribute,
            \Reflector $reflector,
        ): void {
            if (!$reflector instanceof \ReflectionClass) {
                return;
            }
            $method = '__invoke';
            $reflectorMethod = $reflector->getMethod($method);
            /** @var \ReflectionNamedType|null $reflectorMethodType */
            $reflectorMethodType = $reflectorMethod->getParameters()[0]->getType();
            $definition->addTag('messenger.message_handler', [
                'bus' => 'query.bus',
                'method' => $method,
                'handles' => $reflectorMethodType?->getName(),
                'from_transport' => $attribute->transport,
            ]);
        },
    );
};
