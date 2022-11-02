<?php

declare(strict_types=1);

namespace App\Http\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Http\Test\Response\EmptyResponseTest
 */
final class EmptyResponse extends Response
{
    public function __construct(int $status = 204)
    {
        parent::__construct(null, $status, []);
    }
}
