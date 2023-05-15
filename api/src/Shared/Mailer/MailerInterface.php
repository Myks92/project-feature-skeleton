<?php

declare(strict_types=1);

namespace App\Shared\Mailer;

use Throwable;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface MailerInterface
{
    /**
     * Sends the given email message.
     *
     * @param MessageInterface $message the email message instance to be sent
     *
     * @throws Throwable if sending failed
     */
    public function send(MessageInterface $message): void;
}
