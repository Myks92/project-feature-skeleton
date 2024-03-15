<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Channel\Email;

use App\Infrastructure\Notifier\Channel\MessageInterface as BaseMessageInterface;

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
