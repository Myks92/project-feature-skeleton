<?php

declare(strict_types=1);

namespace App\Infrastructure\Filesystem\DirectoryListing;

/**
 * @psalm-import-type TFileAttributes from StorageAttributes
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class FileAttributes implements StorageAttributes
{
    use ProxyArrayAccessToPropertiesTrait;

    /**
     * @var self::TYPE_FILE
     */
    private string $type = StorageAttributes::TYPE_FILE;

    public function __construct(
        private string $path,
        private readonly ?int $fileSize = null,
        private readonly ?string $visibility = null,
        private readonly ?int $lastModified = null,
        private readonly ?string $mimeType = null,
        private readonly array $extraMetadata = [],
    ) {
        $this->path = ltrim($this->path, '/');
    }

    #[\Override]
    public static function fromArray(array $attributes): self
    {
        return new self(
            path: $attributes[StorageAttributes::ATTRIBUTE_PATH],
            fileSize: $attributes[StorageAttributes::ATTRIBUTE_FILE_SIZE] ?? null,
            visibility: $attributes[StorageAttributes::ATTRIBUTE_VISIBILITY] ?? null,
            lastModified: $attributes[StorageAttributes::ATTRIBUTE_LAST_MODIFIED] ?? null,
            mimeType: $attributes[StorageAttributes::ATTRIBUTE_MIME_TYPE] ?? null,
            extraMetadata: $attributes[StorageAttributes::ATTRIBUTE_EXTRA_METADATA] ?? [],
        );
    }

    /**
     * @return self::TYPE_*
     */
    #[\Override]
    public function type(): string
    {
        return $this->type;
    }

    #[\Override]
    public function path(): string
    {
        return $this->path;
    }

    public function fileSize(): ?int
    {
        return $this->fileSize;
    }

    #[\Override]
    public function visibility(): ?string
    {
        return $this->visibility;
    }

    #[\Override]
    public function lastModified(): ?int
    {
        return $this->lastModified;
    }

    public function mimeType(): ?string
    {
        return $this->mimeType;
    }

    #[\Override]
    public function extraMetadata(): array
    {
        return $this->extraMetadata;
    }

    #[\Override]
    public function isFile(): bool
    {
        return true;
    }

    #[\Override]
    public function isDir(): bool
    {
        return false;
    }

    #[\Override]
    public function withPath(string $path): self
    {
        $clone = clone $this;
        $clone->path = $path;

        return $clone;
    }

    /**
     * @return TFileAttributes
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            StorageAttributes::ATTRIBUTE_TYPE => self::TYPE_FILE,
            StorageAttributes::ATTRIBUTE_PATH => $this->path,
            StorageAttributes::ATTRIBUTE_FILE_SIZE => $this->fileSize,
            StorageAttributes::ATTRIBUTE_VISIBILITY => $this->visibility,
            StorageAttributes::ATTRIBUTE_LAST_MODIFIED => $this->lastModified,
            StorageAttributes::ATTRIBUTE_MIME_TYPE => $this->mimeType,
            StorageAttributes::ATTRIBUTE_EXTRA_METADATA => $this->extraMetadata,
        ];
    }
}
