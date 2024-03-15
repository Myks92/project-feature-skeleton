<?php

declare(strict_types=1);

namespace App\Infrastructure;

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
        $projectDir = $this->getProjectDir();

        $container->import($configDir . '/{packages}/*.{php,yaml}');
        $container->import($projectDir . '/src/**/config.php');
    }
}
