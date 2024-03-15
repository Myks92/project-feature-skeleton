<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Channel\Telegram;

use App\Contracts\Notifier\RecipientInterface as BaseRecipientInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface RecipientInterface extends BaseRecipientInterface
{
    /**
     * @return non-empty-string
     */
    public function getChatId(): string;
}
