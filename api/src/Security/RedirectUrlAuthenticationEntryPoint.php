<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class RedirectUrlAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly string $loginUrl,
        private readonly string $targetPathParameter,
    ) {
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $redirectUrl = $this->loginUrl . '?' . $this->targetPathParameter . '=' . $request->getUri();
        return new RedirectResponse($redirectUrl);
    }
}
