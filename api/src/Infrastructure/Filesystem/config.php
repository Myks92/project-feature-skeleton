<?php

declare(strict_types=1);

namespace App\Infrastructure\Filesystem;

use App\Contracts\Filesystem\FilesystemInterface;
use App\Infrastructure\Filesystem\Flysystem\Filesystem;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(FilesystemInterface::class);

    $services->alias(Filesystem::class, FilesystemInterface::class);
};
