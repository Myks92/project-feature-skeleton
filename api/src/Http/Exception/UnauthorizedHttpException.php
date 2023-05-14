<?php

declare(strict_types=1);

namespace App\Http\Exception;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class UnauthorizedHttpException extends \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
{
    public function __construct()
    {
        parent::__construct('', 'Unauthorized');
    }
}