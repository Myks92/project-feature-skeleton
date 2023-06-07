<?php

declare(strict_types=1);

namespace App\Infrastructure\Template;

use App\Contracts\Template\TemplateInterface;
use App\Infrastructure\Template\Twig\TwigTemplate;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(TwigTemplate::class);
    $services->alias(TemplateInterface::class, TwigTemplate::class);
};
