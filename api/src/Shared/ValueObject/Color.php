<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use InvalidArgumentException;

/**
 * @psalm-immutable
 * @template-implements ValueObjectInterface<Color>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract class Color implements ValueObjectInterface
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $color = str_replace('#', '', $value);

        if (\strlen($color) === 3) {
            $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
        }

        if (!preg_match('/^[a-fA-F0-9]+$/', $color)) {
            throw new InvalidArgumentException('HEX color does not match format.');
        }
        if (\strlen($color) !== 6) {
            throw new InvalidArgumentException('HEX color needs to be 6 or 3 digits long.');
        }

        $this->value = $color;
    }

    final public function getValue(): string
    {
        return $this->value;
    }

    final public function isEqual(ValueObjectInterface $object): bool
    {
        return $this->value === $object->getValue();
    }
}
