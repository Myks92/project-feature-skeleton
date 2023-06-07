<?php

declare(strict_types=1);

namespace App\Contracts\Serializer\Normalizer;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface DenormalizerInterface
{
    /**
     * @template T
     * @param class-string<T> $type
     * @return T
     */
    public function denormalize(mixed $data, string $type): object;
}
