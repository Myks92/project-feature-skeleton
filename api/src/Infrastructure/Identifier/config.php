<?php

declare(strict_types=1);

namespace App\Infrastructure\Identifier;

use App\Contracts\Identifier\IdentifierGeneratorInterface;
use App\Infrastructure\Identifier\Ramsey\RamseyIdentifierGenerator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(RamseyIdentifierGenerator::class);

    $services->alias(IdentifierGeneratorInterface::class, RamseyIdentifierGenerator::class);
};
