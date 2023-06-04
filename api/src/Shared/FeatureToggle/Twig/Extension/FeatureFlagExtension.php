<?php

declare(strict_types=1);

namespace App\Shared\FeatureToggle\Twig\Extension;

use App\Shared\FeatureToggle\FeatureFlagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @see \App\Shared\FeatureToggle\Test\Twig\Extension\FeatureFlagExtensionTest
 */
final class FeatureFlagExtension extends AbstractExtension
{
    public function __construct(
        private readonly FeatureFlagInterface $flag
    ) { }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_feature_enabled', $this->isFeatureEnabled(...)),
        ];
    }

    public function isFeatureEnabled(string $name): bool
    {
        return $this->flag->isEnabled($name);
    }
}
