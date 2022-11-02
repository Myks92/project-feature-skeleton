<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private function configureContainer(ContainerConfigurator $container): void
    {
        $configDir = $this->getConfigDir();
        $container->import($configDir . '/{packages}/*.{php,yaml}');
        $container->import($configDir . '/{services}/*.{php,yaml}');
        $container->import('./**/config.{php,yaml}');
    }
}
