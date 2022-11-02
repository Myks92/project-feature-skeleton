<?php

declare(strict_types=1);

namespace App\Http\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Http\Test\Response\HtmlResponseTest
 */
final class HtmlResponse extends Response
{
    public function __construct(string $html, int $status = 200)
    {
        parent::__construct($html, $status, ['Content-Type' => 'text/html']);
    }
}
