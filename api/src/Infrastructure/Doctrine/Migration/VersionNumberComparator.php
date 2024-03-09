<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\Migrations\Version\Comparator;
use Doctrine\Migrations\Version\Version;

final class VersionNumberComparator implements Comparator
{
    #[\Override]
    public function compare(Version $a, Version $b): int
    {
        return strcmp($this->getNumber($a), $this->getNumber($b));
    }

    private function getNumber(Version $current): string
    {
        $parts = explode('\\', (string) $current);

        return end($parts);
    }
}
