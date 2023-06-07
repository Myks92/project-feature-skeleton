<?php

declare(strict_types=1);

namespace App\Shared\Filesystem\Exception;

use App\Contracts\Filesystem\Exception\FilesystemExceptionInterface;
use RuntimeException;
use Throwable;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class FilesystemException extends RuntimeException implements FilesystemExceptionInterface
{
    public static function fromThrowable(Throwable $throwable): self
    {
        return new self(
            message: $throwable->getMessage(),
            code: (int)$throwable->getCode(),
            previous: $throwable,
        );
    }
}
