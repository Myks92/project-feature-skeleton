<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel\Email;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Recipient implements RecipientInterface
{
    /**
     * @param non-empty-string $email
     */
    public function __construct(
        private string $email,
    ) {
        if (empty($email)) {
            throw new \InvalidArgumentException(sprintf('"%s" needs an email but both cannot be empty.', self::class));
        }
    }

    #[\Override]
    public function getEmail(): string
    {
        return $this->email;
    }
}
