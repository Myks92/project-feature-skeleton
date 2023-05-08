<?php

declare(strict_types=1);

namespace App\Shared\Translator;

use InvalidArgumentException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface TranslatorInterface
{
    /**
     * @param string $id The message id (may also be an object that can be cast to string)
     * @param array $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @throws InvalidArgumentException If the locale contains invalid characters
     */
    public function trans(string $id, array $parameters = [], string|null $domain = null, string|null $locale = null): string;
}
