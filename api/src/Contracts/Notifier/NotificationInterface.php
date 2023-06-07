<?php

declare(strict_types=1);

namespace App\Contracts\Notifier;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface NotificationInterface
{
    /**
     * @return non-empty-string|null $subject
     */
    public function getSubject(): ?string;

    /**
     * @return non-empty-string
     */
    public function getContent(): string;
}
