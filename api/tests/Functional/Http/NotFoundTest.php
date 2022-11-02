<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

/**
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class NotFoundTest extends WebTestCase
{
    use ArraySubsetAsserts;

    public function testMethod(): void
    {
        $this->client->jsonRequest('GET', '/not-found');

        $response = $this->client->getResponse();

        self::assertSame(404, $response->getStatusCode());
        self::assertJson($body = (string)$response->getContent());

        $data = Json::decode($body);

        self::assertArraySubset([
            'message' => '404 Not Found',
        ], $data);
    }
}
