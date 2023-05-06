<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel\Telegram;

use InvalidArgumentException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Recipient implements RecipientInterface
{
    /**
     * @param non-empty-string $chatId
     */
    public function __construct(
        private string $chatId
    ) {
        if (empty($chatId)) {
            throw new InvalidArgumentException(sprintf('"%s" needs an chat id but both cannot be empty.', self::class));
        }
    }

    public function getChatId(): string
    {
        return $this->chatId;
    }
}
