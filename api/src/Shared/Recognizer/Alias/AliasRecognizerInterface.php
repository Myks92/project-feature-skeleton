<?php

declare(strict_types=1);

namespace App\Shared\Recognizer\Alias;

use App\Shared\Recognizer\Alias\Exception\AliasNotRecognizedException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface AliasRecognizerInterface
{
    public function supports(mixed $data): bool;

    /**
     * @throws AliasNotRecognizedException
     */
    public function recognize(mixed $data): string;
}
