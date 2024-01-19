<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel\Sms;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Recipient implements RecipientInterface
{
    /**
     * @param non-empty-string $phone
     */
    public function __construct(
        private string $phone,
    ) {
        if (empty($phone)) {
            throw new \InvalidArgumentException(sprintf('"%s" needs an phone but both cannot be empty.', self::class));
        }
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
