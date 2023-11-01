<?php

declare(strict_types=1);

namespace App\Infrastructure\FeatureToggle\Memory;

use App\Contracts\FeatureToggle\FeatureContextInterface;
use App\Contracts\FeatureToggle\FeatureFlagInterface;
use App\Contracts\FeatureToggle\FeatureSwitcherInterface;

final class MemoryFeatures implements FeatureFlagInterface, FeatureSwitcherInterface, FeatureContextInterface
{
    /**
     * @param array<string, bool> $features
     */
    public function __construct(
        private array $features
    ) {}

    public function isEnabled(string $name): bool
    {
        if (!\array_key_exists($name, $this->features)) {
            return false;
        }
        return $this->features[$name];
    }

    public function enable(string $name): void
    {
        $this->features[$name] = true;
    }

    public function disable(string $name): void
    {
        $this->features[$name] = false;
    }

    /**
     * @return string[]
     */
    public function getAllEnabled(): array
    {
        return array_keys(array_filter($this->features));
    }
}
