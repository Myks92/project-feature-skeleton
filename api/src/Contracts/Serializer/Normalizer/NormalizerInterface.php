<?php

declare(strict_types=1);

namespace App\Contracts\Serializer\Normalizer;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface NormalizerInterface
{
    public function normalize(mixed $object): null|array|\ArrayObject|bool|float|int|string;
}
