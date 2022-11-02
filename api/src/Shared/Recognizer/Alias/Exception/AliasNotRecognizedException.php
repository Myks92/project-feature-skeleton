<?php

declare(strict_types=1);

namespace App\Shared\Recognizer\Alias\Exception;

use Exception;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class AliasNotRecognizedException extends Exception
{
    public function __construct()
    {
        parent::__construct('The alias is not recognized.');
    }
}
