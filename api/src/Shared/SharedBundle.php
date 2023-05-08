<?php

declare(strict_types=1);

namespace App\Shared;

use App\Shared\Bus\Command\Attribute\CommandHandler;
use App\Shared\Bus\Command\CommandHandlerInterface;
use App\Shared\Bus\Event\Attribute\EventHandler;
use App\Shared\Bus\Event\EventHandlerInterface;
use App\Shared\Bus\Query\Attribute\QueryHandler;
use App\Shared\Bus\Query\QueryHandlerInterface;
use App\Shared\DomainEvent\Attribute\EventListener;
use App\Shared\DomainEvent\EventListenerInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use Reflector;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class SharedBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        $this->registerCommandBus($container);
        $this->registerQueryBus($container);
        $this->registerEventBus($container);
        $this->registerEventListener($container);

        parent::build($container);
    }

    private function registerCommandBus(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(CommandHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'command.bus']);

        $container->registerAttributeForAutoconfiguration(
            CommandHandler::class,
            static function (
                ChildDefinition $definition,
                CommandHandler $attribute,
                Reflector $reflector,
            ): void {
                if (!$reflector instanceof ReflectionClass) {
                    return;
                }
                $method = '__invoke';
                $reflectorMethod = $reflector->getMethod($method);
                /** @var ReflectionNamedType|null $reflectorMethodType */
                $reflectorMethodType = $reflectorMethod->getParameters()[0]->getType();
                $definition->addTag('messenger.message_handler', [
                    'bus' => 'command.bus',
                    'method' => $method,
                    'handles' => $reflectorMethodType?->getName(),
                    'from_transport' => $attribute->async ? 'async' : 'sync',
                ]);
            }
        );
    }

    private function registerQueryBus(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(QueryHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'query.bus']);

        $container->registerAttributeForAutoconfiguration(
            QueryHandler::class,
            static function (
                ChildDefinition $definition,
                QueryHandler $attribute,
                Reflector $reflector,
            ): void {
                if (!$reflector instanceof ReflectionClass) {
                    return;
                }
                $method = '__invoke';
                $reflectorMethod = $reflector->getMethod($method);
                /** @var ReflectionNamedType|null $reflectorMethodType */
                $reflectorMethodType = $reflectorMethod->getParameters()[0]->getType();
                $definition->addTag('messenger.message_handler', [
                    'bus' => 'query.bus',
                    'method' => $method,
                    'handles' => $reflectorMethodType?->getName(),
                    'from_transport' => $attribute->async ? 'async' : 'sync',
                ]);
            }
        );
    }

    private function registerEventBus(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(EventHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'event.bus']);

        $container->registerAttributeForAutoconfiguration(
            EventHandler::class,
            static function (
                ChildDefinition $definition,
                EventHandler $attribute,
                ReflectionClass|ReflectionMethod|Reflector $reflector,
            ): void {
                $method = ($reflector instanceof ReflectionMethod) ? $reflector->getName() : '__invoke';
                $definition->addTag('messenger.message_handler', [
                    'bus' => 'event.bus',
                    'method' => $method,
                    'handles' => $attribute->event,
                    'from_transport' => $attribute->async ? 'async' : 'sync',
                    'priority' => $attribute->priority,
                ]);
            }
        );
    }

    private function registerEventListener(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(EventListenerInterface::class)
            ->addTag('kernel.event_listener');

        $container->registerAttributeForAutoconfiguration(
            EventListener::class,
            static function (
                ChildDefinition $definition,
                EventListener $attribute,
                ReflectionClass|ReflectionMethod|Reflector $reflector,
            ): void {
                $method = ($reflector instanceof ReflectionMethod) ? $reflector->getName() : '__invoke';
                $definition->addTag('kernel.event_listener', [
                    'method' => $method,
                    'handles' => $attribute->event,
                    'priority' => $attribute->priority,
                ]);
            }
        );
    }
}
