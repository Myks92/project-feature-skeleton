<?php

declare(strict_types=1);

namespace App\Shared\Mailer\Adapter\Symfony;

use App\Shared\Mailer\MailerInterface;
use App\Shared\Mailer\MessageInterface;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailer;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Mailer\Test\Adapter\Symfony\MailerTest
 */
final readonly class Mailer implements MailerInterface
{
    public function __construct(
        private SymfonyMailer $mailer,
    ) {
    }

    public function send(MessageInterface $message): void
    {
        $email = MessageConverter::toEmail($message);
        $this->mailer->send($email);
    }
}
