<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Shared\Bus\Command\Attribute\CommandHandler;
use App\Shared\Bus\Event\Attribute\EventHandler;
use App\Shared\Bus\Query\Attribute\QueryHandler;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class InfrastructureBundle extends AbstractBundle
{
    #[\Override]
    public function build(ContainerBuilder $container): void
    {
        $this->registerCommandBus($container);
        $this->registerQueryBus($container);
        $this->registerEventBus($container);

        parent::build($container);
    }

    private function registerCommandBus(ContainerBuilder $container): void
    {
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
    }

    private function registerQueryBus(ContainerBuilder $container): void
    {
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
    }

    private function registerEventBus(ContainerBuilder $container): void
    {
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
    }
}
