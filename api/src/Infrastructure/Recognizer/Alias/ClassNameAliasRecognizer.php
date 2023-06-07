<?php

declare(strict_types=1);

namespace App\Infrastructure\Recognizer\Alias;

use App\Contracts\Recognizer\Alias\AliasRecognizerInterface;
use App\Infrastructure\Recognizer\Alias\Exception\AliasNotRecognizedException;

/**
 * @template T as class-string
 * @template-implements AliasRecognizerInterface<T>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class ClassNameAliasRecognizer implements AliasRecognizerInterface
{
    public function supports(mixed $data): bool
    {
        return \is_string($data) && class_exists($data, false);
    }

    public function recognize(mixed $data): string
    {
        if (!$this->supports($data)) {
            throw new AliasNotRecognizedException();
        }
        return $data;
    }
}
