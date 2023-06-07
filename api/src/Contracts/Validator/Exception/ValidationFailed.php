<?php

declare(strict_types=1);

namespace App\Contracts\Validator\Exception;

use App\Contracts\Validator\Errors;
use Throwable;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface ValidationFailed extends Throwable
{
    public function getErrors(): Errors;
}
