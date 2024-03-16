<?php

declare(strict_types=1);

namespace App\Infrastructure\Flusher;

use App\Contracts\Flusher\FlusherInterface;
use App\Infrastructure\Flusher\All\AllFlusher;
use App\Infrastructure\Flusher\Doctrine\DoctrineFlusher;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(EventBusFlusher::class)->tag(FlusherInterface::class, ['priority' => -910]);
    $services->set(DoctrineFlusher::class)->tag(FlusherInterface::class, ['priority' => -915]);
    $services->set(AllFlusher::class)->args([
        '$flushers' => tagged_iterator(FlusherInterface::class),
    ]);

    $services->alias(FlusherInterface::class, AllFlusher::class);
};
