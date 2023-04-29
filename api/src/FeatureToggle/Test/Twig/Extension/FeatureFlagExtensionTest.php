<?php

declare(strict_types=1);

namespace App\FeatureToggle\Test\Twig\Extension;

use App\FeatureToggle\FeatureFlagInterface;
use App\FeatureToggle\Twig\Extension\FeatureFlagExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * @internal
 */
#[CoversClass(FeatureFlagExtension::class)]
final class FeatureFlagExtensionTest extends TestCase
{
    public function testEnabled(): void
    {
        $flag = $this->createMock(FeatureFlagInterface::class);
        $flag->expects(self::once())->method('isEnabled')->with('ONE')->willReturn(true);

        $twig = new Environment(new ArrayLoader([
            'page.html.twig' => '<p>{{ is_feature_enabled(\'ONE\') ? \'true\' : \'false\' }}</p>',
        ]));

        $twig->addExtension(new FeatureFlagExtension($flag));

        self::assertSame('<p>true</p>', $twig->render('page.html.twig'));
    }

    public function testNotEnabled(): void
    {
        $flag = $this->createMock(FeatureFlagInterface::class);
        $flag->expects(self::once())->method('isEnabled')->with('ONE')->willReturn(false);

        $twig = new Environment(new ArrayLoader([
            'page.html.twig' => '<p>{{ is_feature_enabled(\'ONE\') ? \'true\' : \'false\' }}</p>',
        ]));

        $twig->addExtension(new FeatureFlagExtension($flag));

        self::assertSame('<p>false</p>', $twig->render('page.html.twig'));
    }
}
