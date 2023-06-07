<?php

declare(strict_types=1);

namespace App\Infrastructure\Paginator;

use App\Contracts\Paginator\PaginatorInterface;
use App\Infrastructure\Paginator\Knp\KnpPaginator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(KnpPaginator::class);
    $services->alias(PaginatorInterface::class, KnpPaginator::class);
};
