<?php

declare(strict_types=1);

namespace App\Infrastructure\Recognizer\Alias;

use App\Contracts\Recognizer\Alias\AliasRecognizerInterface;
use App\Infrastructure\Recognizer\Alias\Exception\AliasNotRecognizedException;
use App\Shared\Assert;

/**
 * @template T
 * @template-implements AliasRecognizerInterface<T>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class AllRecognizer implements AliasRecognizerInterface
{
    /**
     * @param iterable<AliasRecognizerInterface> $aliases
     */
    public function __construct(
        private iterable $aliases,
    ) {
        Assert::allIsInstanceOf($aliases, AliasRecognizerInterface::class);
    }

    #[\Override]
    public function supports(mixed $data): bool
    {
        foreach ($this->aliases as $alias) {
            if ($alias->supports($data)) {
                return true;
            }
        }

        return false;
    }

    #[\Override]
    public function recognize(mixed $data): string
    {
        foreach ($this->aliases as $alias) {
            if ($alias->supports($data)) {
                return $alias->recognize($data);
            }
        }

        throw new AliasNotRecognizedException();
    }
}
