<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use App\Shared\Assert;
use ReflectionClass;
use Stringable;

/**
 * @template T
 * @template-implements ValueObjectInterface<Enum>
 * @psalm-immutable
 * @psalm-consistent-constructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\ValueObject\Test\EnumTest
 */
abstract class Enum implements ValueObjectInterface, Stringable
{
    protected static array $cache = [];
    /**
     * @var T
     */
    protected mixed $value;

    /**
     * @param T $value
     */
    public function __construct(mixed $value)
    {
        Assert::notEmpty($value);
        Assert::oneOf($value, static::toArray());
        $this->value = $value;
    }

    public static function __callStatic(string $name, array $arguments): static
    {
        $array = static::toArray();
        $name = static::convertUnCamelCase($name);

        Assert::keyExists($array, $name, "No static method or enum constant '{$name}' in class " . static::class);
        /** @psalm-suppress UnsafeGenericInstantiation */
        return new static($array[$name]);
    }

    public function __toString(): string
    {
        return (string)$this->getValue();
    }

    /**
     * @return T
     */
    final public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     * @return mixed[]
     */
    final public static function toArray(): array
    {
        $class = static::class;

        if (!isset(static::$cache[$class])) {
            $reflection = new ReflectionClass($class);
            $constants = $reflection->getConstants();
            static::$cache[$class] = $constants;
        }

        return static::$cache[$class];
    }

    final public function isEqual(ValueObjectInterface $object): bool
    {
        return $this->getValue() === $object->getValue();
    }

    /**
     * Converter to un camel Case.
     *
     * Using for constant `STATUS_NAME` to `statusName`
     */
    private static function convertUnCamelCase(string $name): string
    {
        return strtoupper(preg_replace('/([a-z])([A-Z])/', '\\1_\\2', $name));
    }
}
