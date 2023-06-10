<?php

declare(strict_types=1);

namespace App\Shared\Filesystem\DirectoryListing;

use ArrayAccess;
use JsonSerializable;

/**
 * @psalm-type TFileAttributes = array{type: self::TYPE_FILE, path: string, file_size: int|null, visibility: string|null, last_modified: int|null, mime_type: string|null, extra_metadata: array}
 * @psalm-type TDirectoryAttributes = array{type: self::TYPE_DIRECTORY, path: string, visibility: string|null, last_modified: int|null, extra_metadata: array}
 *
 * @template-extends ArrayAccess<mixed, mixed>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface StorageAttributes extends JsonSerializable, ArrayAccess
{
    public const ATTRIBUTE_PATH = 'path';
    public const ATTRIBUTE_TYPE = 'type';
    public const ATTRIBUTE_FILE_SIZE = 'file_size';
    public const ATTRIBUTE_VISIBILITY = 'visibility';
    public const ATTRIBUTE_LAST_MODIFIED = 'last_modified';
    public const ATTRIBUTE_MIME_TYPE = 'mime_type';
    public const ATTRIBUTE_EXTRA_METADATA = 'extra_metadata';
    public const TYPE_FILE = 'file';
    public const TYPE_DIRECTORY = 'dir';

    public function path(): string;

    /**
     * @return self::TYPE_*
     */
    public function type(): string;

    public function visibility(): string|null;

    public function lastModified(): int|null;

    /**
     * @param TDirectoryAttributes|TFileAttributes $attributes
     */
    public static function fromArray(array $attributes): self;

    public function isFile(): bool;

    public function isDir(): bool;

    public function withPath(string $path): self;

    public function extraMetadata(): array;
}
