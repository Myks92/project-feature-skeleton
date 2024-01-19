<?php

declare(strict_types=1);

namespace App\Shared\Filesystem\DirectoryListing;

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
    public function type(): string
    {
        return $this->type;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function fileSize(): ?int
    {
        return $this->fileSize;
    }

    public function visibility(): ?string
    {
        return $this->visibility;
    }

    public function lastModified(): ?int
    {
        return $this->lastModified;
    }

    public function mimeType(): ?string
    {
        return $this->mimeType;
    }

    public function extraMetadata(): array
    {
        return $this->extraMetadata;
    }

    public function isFile(): bool
    {
        return true;
    }

    public function isDir(): bool
    {
        return false;
    }

    public function withPath(string $path): self
    {
        $clone = clone $this;
        $clone->path = $path;

        return $clone;
    }

    /**
     * @return TFileAttributes
     */
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
