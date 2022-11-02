<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (ContainerConfigurator $configurator): void {
    $configurator->parameters()
        ->set('security.login_url', env('SECURITY_LOGIN_URL'));

    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Security\\', '.')->exclude([
        './config.php',
        './Test',
    ]);
};
