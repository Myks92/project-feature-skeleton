<?php

declare(strict_types=1);

namespace App\Shared\Template;

use Twig\Environment;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Template\Test\TwigTemplateTest
 */
final readonly class TwigTemplate implements TemplateInterface
{
    public function __construct(
        public Environment $twig,
    ) {
    }

    public function render(string $name, array $context = []): string
    {
        return $this->twig->render($name, $context);
    }
}
