<?php

declare(strict_types=1);

namespace App\Infrastructure\FeatureToggle\Memory\Test;

use App\Infrastructure\FeatureToggle\Memory\MemoryFeatures;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(MemoryFeatures::class)]
final class FeaturesTest extends TestCase
{
    public function testInitial(): void
    {
        $features = new MemoryFeatures([
            'FIRST' => true,
            'SECOND' => false,
        ]);

        self::assertTrue($features->isEnabled('FIRST'));
        self::assertFalse($features->isEnabled('SECOND'));
        self::assertFalse($features->isEnabled('THIRD'));

        self::assertSame(['FIRST'], $features->getAllEnabled());
    }

    public function testEnable(): void
    {
        $features = new MemoryFeatures([
            'FIRST' => false,
            'SECOND' => false,
        ]);

        $features->enable('SECOND');
        $features->enable('THIRD');

        self::assertFalse($features->isEnabled('FIRST'));
        self::assertTrue($features->isEnabled('SECOND'));
        self::assertTrue($features->isEnabled('THIRD'));

        self::assertSame(['SECOND', 'THIRD'], $features->getAllEnabled());
    }

    public function testDisable(): void
    {
        $features = new MemoryFeatures([
            'FIRST' => false,
            'SECOND' => true,
        ]);

        $features->disable('SECOND');
        $features->disable('THIRD');

        self::assertFalse($features->isEnabled('FIRST'));
        self::assertFalse($features->isEnabled('SECOND'));
        self::assertFalse($features->isEnabled('THIRD'));
    }
}
