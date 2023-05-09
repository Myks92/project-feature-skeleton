<?php

declare(strict_types=1);

namespace App\Shared\Filesystem\DirectoryListing;

/**
 * @psalm-import-type TDirectoryAttributes from StorageAttributes
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class DirectoryAttributes implements StorageAttributes
{
    use ProxyArrayAccessToPropertiesTrait;

    /**
     * @var self::TYPE_DIRECTORY
     */
    private string $type = StorageAttributes::TYPE_DIRECTORY;

    public function __construct(
        private string $path,
        private readonly ?string $visibility = null,
        private readonly ?int $lastModified = null,
        private readonly array $extraMetadata = []
    ) {
        $this->path = ltrim($this->path, '/');
    }

    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return self::TYPE_*
     */
    public function type(): string
    {
        return $this->type;
    }

    public function visibility(): ?string
    {
        return $this->visibility;
    }

    public function lastModified(): ?int
    {
        return $this->lastModified;
    }

    public function extraMetadata(): array
    {
        return $this->extraMetadata;
    }

    public function isFile(): bool
    {
        return false;
    }

    public function isDir(): bool
    {
        return true;
    }

    public function withPath(string $path): self
    {
        $clone = clone $this;
        $clone->path = $path;

        return $clone;
    }

    public static function fromArray(array $attributes): self
    {
        return new self(
            path: $attributes[StorageAttributes::ATTRIBUTE_PATH],
            visibility: $attributes[StorageAttributes::ATTRIBUTE_VISIBILITY] ?? null,
            lastModified: $attributes[StorageAttributes::ATTRIBUTE_LAST_MODIFIED] ?? null,
            extraMetadata: $attributes[StorageAttributes::ATTRIBUTE_EXTRA_METADATA] ?? []
        );
    }

    /**
     * @return TDirectoryAttributes
     */
    public function jsonSerialize(): array
    {
        return [
            StorageAttributes::ATTRIBUTE_TYPE => $this->type,
            StorageAttributes::ATTRIBUTE_PATH => $this->path,
            StorageAttributes::ATTRIBUTE_VISIBILITY => $this->visibility,
            StorageAttributes::ATTRIBUTE_LAST_MODIFIED => $this->lastModified,
            StorageAttributes::ATTRIBUTE_EXTRA_METADATA => $this->extraMetadata,
        ];
    }
}
