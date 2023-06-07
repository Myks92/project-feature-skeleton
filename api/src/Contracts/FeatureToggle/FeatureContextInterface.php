<?php

declare(strict_types=1);

namespace App\Contracts\FeatureToggle;

interface FeatureContextInterface
{
    /**
     * @return string[]
     */
    public function getAllEnabled(): array;
}
