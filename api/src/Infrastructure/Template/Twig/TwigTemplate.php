<?php

declare(strict_types=1);

namespace App\Infrastructure\Template\Twig;

use App\Contracts\Template\TemplateInterface;
use Twig\Environment;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class TwigTemplate implements TemplateInterface
{
    public function __construct(
        public Environment $twig,
    ) {}

    public function render(string $name, array $context = []): string
    {
        return $this->twig->render($name, $context);
    }
}
