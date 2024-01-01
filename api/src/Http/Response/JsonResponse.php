<?php

declare(strict_types=1);

namespace App\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class JsonResponse extends SymfonyJsonResponse
{
    public function __construct(mixed $data, int $status = 200)
    {
        parent::__construct($data, $status);
    }
}
