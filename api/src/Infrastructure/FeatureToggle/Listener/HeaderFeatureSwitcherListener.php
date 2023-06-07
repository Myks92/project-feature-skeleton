<?php

declare(strict_types=1);

namespace App\Infrastructure\FeatureToggle\Listener;

use App\Contracts\FeatureToggle\FeatureSwitcherInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[AsEventListener(event: KernelEvents::REQUEST)]
final readonly class HeaderFeatureSwitcherListener
{
    public function __construct(
        public FeatureSwitcherInterface $switcher,
        public string $key = 'X-Features'
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $header = $request->headers->get($this->key, '');
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
