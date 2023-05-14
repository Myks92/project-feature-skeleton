<?php

declare(strict_types=1);

namespace App\Http;

use App\Http\Authenticator\RedirectUrlAuthenticationEntryPoint;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (ContainerConfigurator $configurator): void {
    $configurator->parameters()
        ->set('security.login_url_default', 'http://localhost')
        ->set('security.login_url', env('SECURITY_LOGIN_URL')->default('security.login_url_default'))
        ->set('security.target_path_parameter_default', 'redirect_url')
        ->set('security.target_path_parameter', env('SECURITY_TARGET_PATH_PARAMETER')->default('security.target_path_parameter_default'));

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

    $services->set(RedirectUrlAuthenticationEntryPoint::class)
        ->args([
            '$loginUrl' => '%security.login_url%',
            '$targetPathParameter' => '%security.target_path_parameter%',
        ]);
};
