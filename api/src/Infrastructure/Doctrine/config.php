<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\Infrastructure\Doctrine\Migration\VersionNumberComparator;
use App\Infrastructure\Doctrine\Types\JsonUnescapedType;
use Doctrine\DBAL\Types\Types as DoctrineTypes;
use Doctrine\Migrations\Version\Comparator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $configurator->extension('doctrine', [
        'dbal' => [
            'types' => [
                DoctrineTypes::JSON => JsonUnescapedType::class,
            ],
        ],
    ]);

    $configurator->extension('doctrine_migrations', [
        'services' => [
            Comparator::class => VersionNumberComparator::class,
        ],
    ]);

    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(FixPostgreSQLDefaultSchemaListener::class);

    $services->set(VersionNumberComparator::class)
        ->tag('doctrine.migrations.dependency_factory');
};
