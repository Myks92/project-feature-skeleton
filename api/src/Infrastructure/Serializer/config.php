<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use App\Contracts\Serializer\Normalizer\DenormalizerInterface;
use App\Contracts\Serializer\Normalizer\NormalizerInterface;
use App\Contracts\Serializer\SerializerInterface;
use App\Infrastructure\Serializer\Symfony\Normalizer\Denormalizer;
use App\Infrastructure\Serializer\Symfony\Normalizer\Normalizer;
use App\Infrastructure\Serializer\Symfony\Serializer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(Serializer::class);
    $services->alias(SerializerInterface::class, Serializer::class);

    $services->set(Denormalizer::class);
    $services->alias(DenormalizerInterface::class, Denormalizer::class);

    $services->set(Normalizer::class);
    $services->alias(NormalizerInterface::class, Normalizer::class);
};
