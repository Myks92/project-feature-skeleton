<?php

declare(strict_types=1);

namespace App\Http\Authentication;

use RuntimeException;
use Throwable;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class UnauthorizedException extends RuntimeException
{
    public function __construct(string $message = 'Unauthorized', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}