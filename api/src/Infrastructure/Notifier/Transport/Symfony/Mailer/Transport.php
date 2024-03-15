<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Transport\Symfony\Mailer;

use App\Infrastructure\Notifier\Channel\Email\MessageInterface as EmailMessageInterface;
use App\Infrastructure\Notifier\Channel\MessageInterface;
use App\Infrastructure\Notifier\Transport\Exception\MessageSendException;
use App\Infrastructure\Notifier\Transport\TransportInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @template T of MessageInterface
 * @template-implements TransportInterface<T>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Transport implements TransportInterface
{
    public function __construct(
        private MailerInterface $mailer,
    ) {}

    #[\Override]
    public function supports(MessageInterface $message): bool
    {
        return $message instanceof EmailMessageInterface;
    }

    #[\Override]
    public function send(MessageInterface $message): void
    {
        if (!$message instanceof EmailMessageInterface) {
            throw new \LogicException(sprintf(
                'The message must be an instance of %s (%s given).',
                EmailMessageInterface::class,
                $message::class,
            ));
        }

        $email = (new Email())
            ->to($message->getTo())
            ->subject($message->getSubject())
            ->html($message->getContent());

        try {
            $this->mailer->send($email);
        } catch (\Exception $exception) {
            throw new MessageSendException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }
}
