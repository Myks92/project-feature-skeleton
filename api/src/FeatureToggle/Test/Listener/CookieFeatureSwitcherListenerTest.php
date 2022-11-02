<?php

declare(strict_types=1);

namespace App\FeatureToggle\Test\Listener;

use App\FeatureToggle\FeatureSwitcherInterface;
use App\FeatureToggle\Listener\CookieFeatureSwitcherListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @covers \App\FeatureToggle\Listener\CookieFeatureSwitcherListener
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class CookieFeatureSwitcherListenerTest extends TestCase
{
    public function testEmpty(): void
    {
        $switcher = $this->createMock(FeatureSwitcherInterface::class);
        $switcher->expects(self::never())->method('enable');
        $switcher->expects(self::never())->method('disable');

        $listener = new CookieFeatureSwitcherListener($switcher, 'X-Features');

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
        $switcher->expects(self::exactly(2))->method('enable')->withConsecutive(['ONE'], ['TWO']);
        $switcher->expects(self::once())->method('disable')->withConsecutive(['THREE']);

        $listener = new CookieFeatureSwitcherListener($switcher, 'X-Features');

        $request = self::createRequest();
        $request->cookies->set('X-Features', 'ONE, TWO, !THREE');

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
