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
        $this->client->jsonRequest('POST', '/');

        $response = $this->client->getResponse();

        self::assertSame(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $this->client->jsonRequest('GET', '/');

        $response = $this->client->getResponse();

        self::assertSame(200, $response->getStatusCode());
        self::assertJson($content = (string)$response->getContent());
        self::assertSame('{"name":"API"}', $content);
    }
}
