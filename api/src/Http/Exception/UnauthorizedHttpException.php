<?php

declare(strict_types=1);

namespace App\Http\Exception;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException as SymfonyUnauthorizedHttpException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class UnauthorizedHttpException extends SymfonyUnauthorizedHttpException
{
    public function __construct()
    {
        parent::__construct('', 'Unauthorized');
    }
}
