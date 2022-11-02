<?php

declare(strict_types=1);

namespace App\Http\Response;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Http\Test\Response\JsonResponseTest
 */
final class JsonResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{
    public function __construct(mixed $data, int $status = 200)
    {
        parent::__construct($data, $status);
    }
}
