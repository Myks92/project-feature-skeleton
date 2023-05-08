<?php

declare(strict_types=1);

namespace App\Shared\Translator\Adapter\Symfony;

use App\Shared\Translator\TranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface as SymfonyTranslatorInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Translator implements TranslatorInterface
{
    public function __construct(
        private SymfonyTranslatorInterface $translator,
    ) {
    }

    public function trans(string $id, array $parameters = [], string|null $domain = null, string|null $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
