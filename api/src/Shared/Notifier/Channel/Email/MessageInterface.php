<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel\Email;

use App\Shared\Notifier\Channel\MessageInterface as BaseMessageInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface MessageInterface extends BaseMessageInterface
{
    /**
     * @return non-empty-string
     */
    public function getSubject(): string;

    /**
     * @return non-empty-string
     */
    public function getTo(): string;
}
