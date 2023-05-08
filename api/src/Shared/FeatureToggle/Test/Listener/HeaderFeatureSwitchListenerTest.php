<?php

declare(strict_types=1);

namespace App\Shared\FeatureToggle\Test\Listener;

use App\Shared\FeatureToggle\FeatureSwitcherInterface;
use App\Shared\FeatureToggle\Listener\HeaderFeatureSwitcherListener;
use App\Shared\PHPUnit\ConsecutiveTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(HeaderFeatureSwitcherListener::class)]
final class HeaderFeatureSwitchListenerTest extends TestCase
{
    use ConsecutiveTrait;

    public function testEmpty(): void
    {
        $switcher = $this->createMock(FeatureSwitcherInterface::class);
        $switcher->expects(self::never())->method('enable');
        $switcher->expects(self::never())->method('disable');

        $listener = new HeaderFeatureSwitcherListener($switcher, 'X-Features');

        $request = self::createRequest();

        $event = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST,
        );

        $listener($event);
    }

    public function testWithFeatures(): void
    {
        $switcher = $this->createMock(FeatureSwitcherInterface::class);
        $switcher->expects(self::exactly(2))->method('enable')->with(self::consecutiveCalls('ONE', 'TWO'));
        $switcher->expects(self::once())->method('disable')->with('THREE');

        $listener = new HeaderFeatureSwitcherListener($switcher, 'X-Features');

        $request = self::createRequest();
        $request->headers->set('X-Features', 'ONE, TWO, !THREE');

        $event = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST,
        );

        $listener($event);
    }

    private static function createRequest(): Request
    {
        return Request::create('GET', 'http://test');
    }
}