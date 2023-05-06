<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface MessageInterface
{
    /**
     * @return non-empty-string
     */
    public function getContent(): string;
}
