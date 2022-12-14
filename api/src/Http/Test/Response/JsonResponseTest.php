<?php

declare(strict_types=1);

namespace App\Http\Test\Response;

use App\Http\Response\JsonResponse;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \App\Http\Response\JsonResponse
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class JsonResponseTest extends TestCase
{
    public function testWithCode(): void
    {
        $response = new JsonResponse(0, 201);

        self::assertSame('application/json', $response->headers->get('Content-Type'));
        self::assertSame('0', $response->getContent());
        self::assertSame(201, $response->getStatusCode());
    }

    /**
     * @dataProvider getCases
     * @noRector UnionTypesRector
     */
    public function testResponse(mixed $source, mixed $expect): void
    {
        $response = new JsonResponse($source);

        self::assertSame('application/json', $response->headers->get('Content-Type'));
        self::assertSame($expect, $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * @return iterable<array-key, array>
     */
    public function getCases(): iterable
    {
        $object = new stdClass();
        $object->str = 'value';
        $object->int = 1;
        $object->none = null;

        $array = [
            'str' => 'value',
            'int' => 1,
            'none' => null,
        ];

        return [
            'null' => [null, '{}'],
            'empty' => ['', '""'],
            'number' => [12, '12'],
            'string' => ['12', '"12"'],
            'object' => [$object, '{"str":"value","int":1,"none":null}'],
            'array' => [$array, '{"str":"value","int":1,"none":null}'],
        ];
    }
}
