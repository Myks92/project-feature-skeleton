<?php

declare(strict_types=1);

namespace App\Shared\Doctrine;

use App\Shared\Doctrine\Types\JsonUnescapedType;
use Doctrine\DBAL\Types\Types as DoctrineTypes;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

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

    $services->load('App\\Shared\\Doctrine\\', '.')->exclude([
        './config.php',
        './*/Test',
    ]);
};
