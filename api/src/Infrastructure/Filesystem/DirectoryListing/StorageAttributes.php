<?php

declare(strict_types=1);

namespace App\Infrastructure\Filesystem\DirectoryListing;

use ArrayAccess;

/**
 * @psalm-type TFileAttributes = array{type: self::TYPE_FILE, path: string, file_size: int|null, visibility: string|null, last_modified: int|null, mime_type: string|null, extra_metadata: array}
 * @psalm-type TDirectoryAttributes = array{type: self::TYPE_DIRECTORY, path: string, visibility: string|null, last_modified: int|null, extra_metadata: array}
 *
 * @template-extends ArrayAccess<mixed, mixed>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface StorageAttributes extends \JsonSerializable, ArrayAccess
{
    public const string ATTRIBUTE_PATH = 'path';
    public const string ATTRIBUTE_TYPE = 'type';
    public const string ATTRIBUTE_FILE_SIZE = 'file_size';
    public const string ATTRIBUTE_VISIBILITY = 'visibility';
    public const string ATTRIBUTE_LAST_MODIFIED = 'last_modified';
    public const string ATTRIBUTE_MIME_TYPE = 'mime_type';
    public const string ATTRIBUTE_EXTRA_METADATA = 'extra_metadata';
    public const string TYPE_FILE = 'file';
    public const string TYPE_DIRECTORY = 'dir';

    /**
     * @param TDirectoryAttributes|TFileAttributes $attributes
     */
    public static function fromArray(array $attributes): self;

    public function path(): string;

    /**
     * @return self::TYPE_*
     */
    public function type(): string;

    public function visibility(): ?string;

    public function lastModified(): ?int;

    public function isFile(): bool;

    public function isDir(): bool;

    public function withPath(string $path): self;

    public function extraMetadata(): array;
}
