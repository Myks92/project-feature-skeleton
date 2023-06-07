<?php

declare(strict_types=1);

namespace App\Contracts\Recognizer\Alias;

use App\Contracts\Recognizer\Alias\Exception\AliasNotRecognized;

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
     * @throws AliasNotRecognized
     */
    public function recognize(mixed $data): string;
}
