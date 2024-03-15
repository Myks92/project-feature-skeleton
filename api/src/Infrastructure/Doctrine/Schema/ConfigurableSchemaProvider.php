<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Schema;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\Provider\SchemaProvider;

final readonly class ConfigurableSchemaProvider implements SchemaProvider
{
    public function __construct(
        private SchemaProvider $schemaProvider,
        private SchemaConfigurator $schemaConfigurator,
    ) {}

    #[\Override]
    public function createSchema(): Schema
    {
        $schema = $this->schemaProvider->createSchema();
        $this->schemaConfigurator->configureSchema($schema);

        return $schema;
    }
}
