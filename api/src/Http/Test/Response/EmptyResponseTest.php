<?php

declare(strict_types=1);

namespace App\Http\Test\Response;

use App\Http\Response\EmptyResponse;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Http\Response\EmptyResponse
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class EmptyResponseTest extends TestCase
{
    public function testDefault(): void
    {
        $response = new EmptyResponse();

        self::assertSame(204, $response->getStatusCode());
        self::assertFalse($response->headers->has('Content-Type'));

        self::assertSame('', (string)$response->getContent());
        self::assertSame('', $response->getContent());
    }

    public function testWithCode(): void
    {
        $response = new EmptyResponse(201);

        self::assertSame(201, $response->getStatusCode());
    }
}
