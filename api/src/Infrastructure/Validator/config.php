<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator;

use App\Contracts\Validator\ValidatorInterface;
use App\Infrastructure\Validator\Symfony\SymfonyValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(SymfonyValidator::class);
    $services->alias(ValidatorInterface::class, SymfonyValidator::class);
};
