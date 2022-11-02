<?php

declare(strict_types=1);

namespace App\FeatureToggle\Listener;

use App\FeatureToggle\FeatureSwitcherInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\FeatureToggle\Test\Listener\CookieFeatureSwitcherListenerTest
 */
#[AsEventListener(event: KernelEvents::REQUEST)]
final class CookieFeatureSwitcherListener
{
    public function __construct(
        public readonly FeatureSwitcherInterface $switcher,
        public readonly string $key = 'X-Features'
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $header = $request->cookies->get($this->key, '');
        $features = array_filter(preg_split('/\s*,\s*/', $header));

        foreach ($features as $feature) {
            if (str_starts_with($feature, '!')) {
                $this->switcher->disable(substr($feature, 1));
            } else {
                $this->switcher->enable($feature);
            }
        }
    }
}
