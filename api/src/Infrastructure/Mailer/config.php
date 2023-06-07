<?php

declare(strict_types=1);

namespace App\Infrastructure\Mailer;

use App\Contracts\Mailer\MailerInterface;
use App\Infrastructure\Mailer\Symfony\SymfonyMailer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(SymfonyMailer::class);
    $services->alias(MailerInterface::class, SymfonyMailer::class);
};
