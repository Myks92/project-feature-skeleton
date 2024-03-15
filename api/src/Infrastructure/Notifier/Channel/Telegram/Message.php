<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Channel\Telegram;

use App\Contracts\Notifier\NotificationInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class Message implements MessageInterface
{
    /**
     * @param non-empty-string $chatId
     * @param non-empty-string $content
     * @param self::PARSE_MODE_* $parseMode
     */
    public function __construct(
        private string $chatId,
        private string $content,
        private string $parseMode = self::PARSE_MODE_HTML,
    ) {
        if (empty($chatId)) {
            throw new \InvalidArgumentException(sprintf('"%s" needs an chatId, it cannot be empty.', self::class));
        }
        if (empty($content)) {
            throw new \InvalidArgumentException(sprintf('"%s" needs an text, it cannot be empty.', self::class));
        }
    }

    public static function fromNotification(NotificationInterface $notification, RecipientInterface $recipient): self
    {
        return new self($recipient->getChatId(), $notification->getContent());
    }

    /**
     * @param non-empty-string $chatId
     */
    public function chatId(string $chatId): self
    {
        if (empty($chatId)) {
            throw new \InvalidArgumentException(sprintf('"%s" needs an chatId, it cannot be empty.', self::class));
        }
        $this->chatId = $chatId;

        return $this;
    }

    #[\Override]
    public function getChatId(): string
    {
        return $this->chatId;
    }

    /**
     * @param non-empty-string $content
     */
    public function content(string $content): self
    {
        if (empty($content)) {
            throw new \InvalidArgumentException(sprintf('"%s" needs an text, it cannot be empty.', self::class));
        }
        $this->content = $content;

        return $this;
    }

    #[\Override]
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param self::PARSE_MODE_* $parseMode
     */
    public function parseMode(string $parseMode): self
    {
        $this->parseMode = $parseMode;

        return $this;
    }

    /**
     * @return self::PARSE_MODE_*
     */
    #[\Override]
    public function getParseMode(): string
    {
        return $this->parseMode;
    }
}
