<?php

declare(strict_types=1);

namespace App\Shared;

use App\Shared\Doctrine\Types\JsonUnescapedType;
use App\Shared\Flusher\DoctrineFlusher;
use App\Shared\Flusher\EventFlusher;
use App\Shared\Flusher\FlusherInterface;
use App\Shared\Flusher\FlushersFlusher;
use Doctrine\DBAL\Types\Types as DoctrineTypes;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $configurator): void {
    $configurator->extension('doctrine', [
        'dbal' => [
            'types' => [
                DoctrineTypes::JSON => JsonUnescapedType::class,
            ],
        ],
    ]);

    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\', '.')->exclude([
        './config.php',
        './*/Test',
        './ValueObject',
        './Aggregate',
        './FeatureToggle',
        './Notifier',
        './Assert.php',
    ]);

    $services->get(EventFlusher::class)->tag('flusher', ['priority' => -910]);
    $services->get(DoctrineFlusher::class)->tag('flusher', ['priority' => -915]);
    $services->set(FlusherInterface::class, FlushersFlusher::class)->args([tagged_iterator('flusher')]);
};
