<?php

declare(strict_types=1);

namespace App\Shared\FeatureToggle;

interface FeatureContextInterface
{
    /**
     * @return string[]
     */
    public function getAllEnabled(): array;
}
