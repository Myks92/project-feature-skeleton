<?php

declare(strict_types=1);

namespace App\Security\Test;

use App\Security\RedirectUrlAuthenticationEntryPoint;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
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
