<?php

declare(strict_types=1);

namespace App\Infrastructure\Recognizer;

use App\Contracts\Recognizer\Alias\AliasRecognizerInterface;
use App\Infrastructure\Recognizer\Alias\DoctrineEntityTableNameAliasRecognizer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(DoctrineEntityTableNameAliasRecognizer::class);
    $services->alias(AliasRecognizerInterface::class, DoctrineEntityTableNameAliasRecognizer::class);
};
