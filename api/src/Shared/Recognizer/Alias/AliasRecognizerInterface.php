<?php

declare(strict_types=1);

namespace App\Shared\Recognizer\Alias;

use App\Shared\Recognizer\Alias\Exception\AliasNotRecognizedException;

/**
 * @template T
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface AliasRecognizerInterface
{
    /**
     * @param T $data
     */
    public function supports(mixed $data): bool;

    /**
     * @param T $data
     * @throws AliasNotRecognizedException
     */
    public function recognize(mixed $data): string;
}
