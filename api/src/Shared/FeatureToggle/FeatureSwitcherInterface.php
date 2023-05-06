<?php

declare(strict_types=1);

namespace App\Shared\FeatureToggle;

interface FeatureSwitcherInterface
{
    public function enable(string $name): void;

    public function disable(string $name): void;
}
