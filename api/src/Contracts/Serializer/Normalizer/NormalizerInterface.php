<?php

declare(strict_types=1);

namespace App\Contracts\Serializer\Normalizer;

use ArrayObject;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface NormalizerInterface
{
    public function normalize(mixed $object): array|string|int|float|bool|ArrayObject|null;
}
