<?php

declare(strict_types=1);

namespace App\Shared\Filesystem\DirectoryListing;

/**
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
trait ProxyArrayAccessToPropertiesTrait
{
    public function offsetExists(mixed $offset): bool
    {
        $property = $this->formatPropertyName((string) $offset);

        return isset($this->{$property});
    }

    #[\ReturnTypeWillChange]
    public function offsetGet(mixed $offset): mixed
    {
        $property = $this->formatPropertyName((string) $offset);

        return $this->{$property};
    }

    #[\ReturnTypeWillChange]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \RuntimeException('Properties can not be manipulated');
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset(mixed $offset): void
    {
        throw new \RuntimeException('Properties can not be manipulated');
    }

    private function formatPropertyName(string $offset): string
    {
        return str_replace('_', '', lcfirst(ucwords($offset, '_')));
    }
}
