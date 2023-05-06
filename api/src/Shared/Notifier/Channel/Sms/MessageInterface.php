<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel\Sms;

use App\Shared\Notifier\Channel\MessageInterface as BaseMessageInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface MessageInterface extends BaseMessageInterface
{
    /**
     * @return non-empty-string
     */
    public function getPhone(): string;
}
