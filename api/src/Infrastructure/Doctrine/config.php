<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\Infrastructure\Doctrine\Types\JsonUnescapedType;
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
};
