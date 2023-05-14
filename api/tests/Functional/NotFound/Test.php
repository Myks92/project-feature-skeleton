<?php

declare(strict_types=1);

namespace Test\Functional\NotFound;

use Test\Functional\Json;
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
        $this->client->jsonRequest('GET', '/not-found');

        $response = $this->client->getResponse();

        self::assertSame(404, $response->getStatusCode());
        self::assertJson($body = (string)$response->getContent());

        $data = Json::decode($body);

        self::assertSame(['message' => '404 Not Found'], $data);
    }
}
