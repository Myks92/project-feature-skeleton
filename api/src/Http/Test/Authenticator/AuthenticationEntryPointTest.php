<?php

declare(strict_types=1);

namespace App\Http\Test\Authenticator;

use App\Http\Authenticator\RedirectUrlAuthenticationEntryPoint;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(RedirectUrlAuthenticationEntryPoint::class)]
final class AuthenticationEntryPointTest extends TestCase
{
    public function testSuccess(): void
    {
        $entryPoint = new RedirectUrlAuthenticationEntryPoint('http://localhost', 'redirect_url');
        $response = $entryPoint->start(Request::create('', 'GET', [], [], [], ['CONTENT_TYPE' => 'application/json']));

        self::assertSame(302, $response->getStatusCode());
        self::assertSame('http://localhost?redirect_url=http://localhost/', $response->headers->get('Location'));
    }
}
