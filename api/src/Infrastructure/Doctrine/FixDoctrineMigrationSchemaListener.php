<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\ToolEvents;
use Symfony\Component\DependencyInjection\Attribute\When;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[When('dev')]
#[AsDoctrineListener(event: ToolEvents::postGenerateSchema)]
final readonly class FixDoctrineMigrationSchemaListener
{
    public function __construct(
        private TableMetadataStorageConfiguration $configuration,
    ) {}

    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $table = $args->getSchema()->createTable($this->configuration->getTableName());

        $table->addColumn(
            $this->configuration->getVersionColumnName(),
            'string',
            ['notnull' => true, 'length' => $this->configuration->getVersionColumnLength()],
        );

        $table->addColumn($this->configuration->getExecutedAtColumnName(), 'datetime', ['notnull' => false]);
        $table->addColumn($this->configuration->getExecutionTimeColumnName(), 'integer', ['notnull' => false]);

        $table->setPrimaryKey([$this->configuration->getVersionColumnName()]);
    }
}
