<?php

declare(strict_types=1);

namespace App\Shared\Recognizer\Alias;

use App\Shared\Recognizer\Alias\Exception\AliasNotRecognizedException;

/**
 * @template-implements AliasRecognizerInterface<object>
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class ObjectAliasRecognizer implements AliasRecognizerInterface
{
    public function supports(mixed $data): bool
    {
        return \is_object($data) && class_exists($data::class, false);
    }

    public function recognize(mixed $data): string
    {
        if (!$this->supports($data)) {
            throw new AliasNotRecognizedException();
        }
        return $data::class;
    }
}
