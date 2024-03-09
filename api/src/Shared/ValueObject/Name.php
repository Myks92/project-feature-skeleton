<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use App\Contracts\ValueObject\ValueObjectInterface;
use App\Shared\Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @template-implements ValueObjectInterface<Name>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract readonly class Name implements ValueObjectInterface, \Stringable
{
    /**
     * @param non-empty-string $last
     * @param non-empty-string $first
     * @param non-empty-string|null $middle
     */
    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 64)]
        private string $last,
        #[ORM\Column(type: Types::STRING, length: 64)]
        private string $first,
        #[ORM\Column(type: Types::STRING, length: 64, nullable: true)]
        private ?string $middle = null,
    ) {
        Assert::lengthBetween($last, 1, 64);
        Assert::lengthBetween($first, 1, 64);
        if ($middle !== null) {
            Assert::lengthBetween($middle, 1, 64);
        }
        Assert::notEmpty($first);
        Assert::notEmpty($last);
    }

    /**
     * @return non-empty-string
     */
    #[\Override]
    public function __toString(): string
    {
        return $this->getFull();
    }

    /**
     * @return non-empty-string
     */
    final public function getLast(): string
    {
        return $this->last;
    }

    /**
     * @return non-empty-string
     */
    final public function getFirst(): string
    {
        return $this->first;
    }

    /**
     * @return non-empty-string|null
     */
    final public function getMiddle(): ?string
    {
        return $this->middle;
    }

    /**
     * @return non-empty-string
     */
    final public function getFull(string $separator = ' '): string
    {
        /** @var non-empty-string */
        return implode($separator, array_filter([
            $this->last,
            $this->first,
            $this->middle,
        ]));
    }

    #[\Override]
    final public function equals(ValueObjectInterface $object): bool
    {
        return $this->last === $object->getLast()
            && $this->first === $object->getFirst()
            && $this->middle === $object->getMiddle();
    }
}
