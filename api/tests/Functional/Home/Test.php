<?php

declare(strict_types=1);

namespace Test\Functional\Home;

use Test\Functional\WebTestCase;

/**
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class Test extends WebTestCase
{
    public function testMethod(): void
    {
        $response = $this->jsonRequest('POST', '/');

        self::assertSame(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->jsonRequest('GET', '/');

        self::assertSame(200, $response->getStatusCode());
        self::assertJson($content = (string)$response->getContent());
        self::assertSame('{"name":"API"}', $content);
    }
}
