<?php

declare(strict_types=1);

namespace App\FeatureToggle;

interface FeatureContextInterface
{
    /**
     * @return string[]
     */
    public function getAllEnabled(): array;
}
