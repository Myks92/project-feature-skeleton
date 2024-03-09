<?php

declare(strict_types=1);

namespace App\Http\Test\Response;

use App\Http\Response\JsonResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(JsonResponse::class)]
final class JsonResponseTest extends TestCase
{
    public static function getCases(): \Iterator
    {
        $object = new \stdClass();
        $object->str = 'value';
        $object->int = 1;
        $object->none = null;

        $array = [
            'str' => 'value',
            'int' => 1,
            'none' => null,
        ];
        yield 'null' => [null, '{}'];
        yield 'empty' => ['', '""'];
        yield 'number' => [12, '12'];
        yield 'string' => ['12', '"12"'];
        yield 'object' => [$object, '{"str":"value","int":1,"none":null}'];
        yield 'array' => [$array, '{"str":"value","int":1,"none":null}'];
    }

    public function testWithCode(): void
    {
        $response = new JsonResponse(0, 201);

        self::assertSame('application/json', $response->headers->get('Content-Type'));
        self::assertSame('0', $response->getContent());
        self::assertSame(201, $response->getStatusCode());
    }

    /**
     * @noRector UnionTypesRector
     */
    #[DataProvider('getCases')]
    public function testResponse(mixed $source, mixed $expect): void
    {
        $response = new JsonResponse($source);

        self::assertSame('application/json', $response->headers->get('Content-Type'));
        self::assertSame($expect, $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }
}
