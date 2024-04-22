<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\Infrastructure\Doctrine\Migration\VersionNumberComparator;
use App\Infrastructure\Doctrine\Schema\ConfigurableSchemaProvider;
use App\Infrastructure\Doctrine\Schema\SchemaConfigurator;
use App\Infrastructure\Doctrine\Schema\SchemaConfigurators;
use App\Infrastructure\Doctrine\Types\JsonUnescapedType;
use Doctrine\DBAL\Types\Types as DoctrineTypes;
use Doctrine\Migrations\Provider\SchemaProvider;
use Doctrine\Migrations\Version\Comparator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $configurator, ContainerBuilder $container): void {
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
            SchemaProvider::class => ConfigurableSchemaProvider::class,
        ],
    ]);

    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(FixPostgreSQLDefaultSchemaListener::class);
    $services->set(FixDoctrineMigrationSchemaListener::class)->args([
        '$configuration' => service('doctrine.migrations.storage.table_storage'),
    ]);

    $services->set(VersionNumberComparator::class)
        ->tag('doctrine.migrations.dependency_factory');

    $services->set(ConfigurableSchemaProvider::class)
        ->args([
            '$schemaProvider' => inline_service()->factory([service('doctrine.migrations.dependency_factory'), 'getSchemaProvider']),
            '$schemaConfigurator' => inline_service(SchemaConfigurators::class)->args([
                tagged_iterator(SchemaConfigurator::class),
            ]),
        ]);

    $container->registerForAutoconfiguration(SchemaConfigurator::class)->addTag(SchemaConfigurator::class);
};
