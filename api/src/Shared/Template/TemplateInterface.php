<?php

declare(strict_types=1);

namespace App\Shared\Template;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface TemplateInterface
{
    public function render(string $name, array $context = []): string;
}
