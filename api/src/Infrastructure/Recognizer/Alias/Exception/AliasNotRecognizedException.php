<?php

declare(strict_types=1);

namespace App\Infrastructure\Recognizer\Alias\Exception;

use App\Contracts\Recognizer\Alias\Exception\AliasNotRecognized;
use Exception;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class AliasNotRecognizedException extends Exception implements AliasNotRecognized
{
    public function __construct()
    {
        parent::__construct('The alias is not recognized.');
    }
}
