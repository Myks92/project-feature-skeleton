<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel\Email;

use App\Shared\Notifier\NotificationInterface;
use InvalidArgumentException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class Message implements MessageInterface
{
    private array $from = [];

    /**
     * @param non-empty-string $to
     * @param non-empty-string $content
     * @param non-empty-string $subject
     */
    public function __construct(
        private string $to,
        private string $subject,
        private string $content,
    ) {
        if (empty($to)) {
            throw new InvalidArgumentException(sprintf('"%s" needs an email, it cannot be empty.', self::class));
        }
        if (empty($subject)) {
            throw new InvalidArgumentException(sprintf('"%s" needs an subject, it cannot be empty.', self::class));
        }
        if (empty($content)) {
            throw new InvalidArgumentException(sprintf('"%s" needs an text, it cannot be empty.', self::class));
        }
    }

    public static function fromNotification(NotificationInterface $notification, RecipientInterface $recipient): self
    {
        if (empty($recipient->getEmail())) {
            throw new InvalidArgumentException(sprintf('"%s" needs an email, it cannot be empty.', self::class));
        }

        return new self($recipient->getEmail(), $notification->getSubject() ?? 'Notification', $notification->getContent());
    }

    /**
     * @param non-empty-string $address
     */
    public function to(string $address): self
    {
        $this->to = $address;

        return $this;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param non-empty-string $address
     */
    public function from(string $address, ?string $name = null): self
    {
        $this->from = [$address => $name ?? $address];

        return $this;
    }

    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * @param non-empty-string $subject
     */
    public function subject(string $subject): self
    {
        if (empty($subject)) {
            throw new InvalidArgumentException(sprintf('"%s" needs an subject, it cannot be empty.', self::class));
        }
        $this->subject = $subject;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param non-empty-string $content
     */
    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
