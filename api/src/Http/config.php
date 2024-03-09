<?php

declare(strict_types=1);

namespace App\Http;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Http\\Action\\', './Action')
        ->tag('controller.service_arguments');

    $services->load('App\\Http\\', '.')->exclude([
        './config.php',
        './Action',
        './Authentication/Identity',
        './Authentication/UnauthorizedException',
        './Exception',
        './Response',
        './Test',
    ]);

    $services->load('App\\Http\\Response\\', './Response/**/*Factory.php');
};
