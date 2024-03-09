<?php

declare(strict_types=1);

namespace App\Infrastructure\Mailer\Symfony;

use App\Contracts\Mailer\MailerInterface;
use App\Contracts\Mailer\MessageInterface;
use Symfony\Component\Mailer\MailerInterface as AdapterMailer;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class SymfonyMailer implements MailerInterface
{
    public function __construct(
        private AdapterMailer $mailer,
    ) {}

    #[\Override]
    public function send(MessageInterface $message): void
    {
        $email = MessageConverter::toEmail($message);
        $this->mailer->send($email);
    }
}
