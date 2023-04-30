<?php

declare(strict_types=1);

namespace App\Shared\PHPUnit;

use PHPUnit\Framework\Constraint\Callback;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
trait ConsecutiveTrait
{
    /**
     * @psalm-suppress MixedArrayOffset, MixedOperand, MixedAssignment
     */
    public static function consecutiveCalls(string ...$args): Callback
    {
        $count = 0;
        return self::callback(static function (string $arg) use (&$count, $args): bool {
            return $arg === $args[$count++];
        });
    }
}
