<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\Symfony\Constraint;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[\Attribute]
final class EmailNotBlank extends Compound
{
    #[\Override]
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Email(),
        ];
    }
}
