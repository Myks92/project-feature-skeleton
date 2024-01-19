<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel\Sms;

use App\Contracts\Notifier\NotificationInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class Message implements MessageInterface
{
    /**
     * @param non-empty-string $phone
     * @param non-empty-string $content
     */
    public function __construct(
        private string $phone,
        private string $content,
    ) {
        if (empty($phone)) {
            throw new \InvalidArgumentException(sprintf('"%s" needs a phone number, it cannot be empty.', self::class));
        }
        if (empty($content)) {
            throw new \InvalidArgumentException(sprintf('"%s" needs a text, it cannot be empty.', self::class));
        }
    }

    public static function fromNotification(NotificationInterface $notification, RecipientInterface $recipient): self
    {
        return new self($recipient->getPhone(), $notification->getContent());
    }

    /**
     * @param non-empty-string $phone
     */
    public function phone(string $phone): self
    {
        if (empty($phone)) {
            throw new \InvalidArgumentException(sprintf('"%s" needs a phone number, it cannot be empty.', self::class));
        }
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param non-empty-string $content
     */
    public function content(string $content): self
    {
        if (empty($content)) {
            throw new \InvalidArgumentException(sprintf('"%s" needs a text, it cannot be empty.', self::class));
        }
        $this->content = $content;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
