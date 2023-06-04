<?php

declare(strict_types=1);

namespace App\Shared\Validator;

use App\Shared\Validator\Adapter\Validator;
use App\Shared\Validator\ValidatorInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(ValidatorInterface::class, Validator::class);
};
