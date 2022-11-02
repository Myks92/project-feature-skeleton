<?php

declare(strict_types=1);

namespace App\FeatureToggle;

interface FeatureFlagInterface
{
    public function isEnabled(string $name): bool;
}
