<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Schema;

use Doctrine\DBAL\Schema\Schema;

final readonly class SchemaConfigurators implements SchemaConfigurator
{
    /**
     * @param iterable<SchemaConfigurator> $configurators
     */
    public function __construct(
        private iterable $configurators,
    ) {}

    #[\Override]
    public function configureSchema(Schema $schema): void
    {
        foreach ($this->configurators as $configurator) {
            $configurator->configureSchema($schema);
        }
    }
}
