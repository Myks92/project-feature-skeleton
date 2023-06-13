<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier;

use App\Contracts\Notifier\NotifierInterface;
use App\Shared\Notifier\Channel;
use App\Shared\Notifier\Notifier;
use App\Shared\Notifier\Transport;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(Transport\Symfony\Mailer\Transport::class);

    $services->set(Transport\Telegram\Transport::class)->args([
        '$token' => env('NOTIFIER_TELEGRAM_TOKEN'),
    ]);

    $services->set(Transport\SmsRu\Transport::class)->args([
        '$apiId' => env('NOTIFIER_SMS_RU_API_ID'),
    ]);
    $services->set(Transport\Transports::class)->args([
        '$transports' => [
            service(Transport\Telegram\Transport::class),
            service(Transport\Symfony\Mailer\Transport::class),
        ],
    ]);
    $services->set(Transport\Symfony\Messenger\Handler::class)->args([
        '$transport' => service(Transport\Transports::class),
    ]);

    $services->set(Channel\Email\Channel::class)->args([
        '$transport' => service(Transport\Symfony\Mailer\Transport::class),
    ]);
    $services->set(Channel\Sms\Channel::class)->args([
        '$transport' => service(Transport\SmsRu\Transport::class),
    ]);
    $services->set(Channel\Telegram\Channel::class)->args([
        '$transport' => service(Transport\Telegram\Transport::class),
    ]);
    $services->set(Channel\Channels::class)->args([
        '$channels' => [
            'email' => service(Channel\Email\Channel::class),
            'sms' => service(Channel\Sms\Channel::class),
            'telegram' => service(Channel\Telegram\Channel::class),
        ],
    ]);

    $services->set(Notifier\AllAvailableChannelsNotifier::class)->args([
        '$channels' => service(Channel\Channels::class),
    ]);
    $services->set(Notifier\Notifiers::class)->args([
        '$notifiers' => [
            service(Notifier\AllAvailableChannelsNotifier::class),
        ],
    ]);

    $services->alias(NotifierInterface::class, Notifier\Notifiers::class);
};