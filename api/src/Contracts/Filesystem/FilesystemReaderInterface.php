<?php

declare(strict_types=1);

namespace App\Contracts\Filesystem;

use App\Contracts\Filesystem\Exception\FilesystemExceptionInterface;
use DateTimeInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface FilesystemReaderInterface
{
    /**
     * The shallow listings will always provide you with every file and directory in the listed path.
     */
    final public const LIST_SHALLOW = false;

    /**
     * The deep listings may provide you with the directories, but will always return all the files
     * contained in the path.
     */
    final public const LIST_DEEP = true;

    /**
     * @param non-empty-string $path
     *
     * @throws FilesystemExceptionInterface
     */
    public function fileExists(string $path): bool;

    /**
     * @param non-empty-string $path
     *
     * @throws FilesystemExceptionInterface
     */
    public function directoryExists(string $path): bool;

    /**
     * @param non-empty-string $path
     *
     * @throws FilesystemExceptionInterface
     */
    public function has(string $path): bool;

    /**
     * @param non-empty-string $path
     *
     * @return non-empty-string
     *
     * @throws FilesystemExceptionInterface
     */
    public function read(string $path): string;

    /**
     * @param non-empty-string $path
     *
     * @return resource
     *
     * @throws FilesystemExceptionInterface
     */
    public function readStream(string $path);

    /**
     * @param non-empty-string $path
     *
     * @throws FilesystemExceptionInterface
     */
    public function lastModified(string $path): int;

    /**
     * @param non-empty-string $path
     *
     * @throws FilesystemExceptionInterface
     */
    public function fileSize(string $path): int;

    /**
     * @param non-empty-string $path
     *
     * @return non-empty-string
     *
     * @throws FilesystemExceptionInterface
     */
    public function mimeType(string $path): string;

    /**
     * @param non-empty-string $path
     *
     * @return non-empty-string
     *
     * @throws FilesystemExceptionInterface
     */
    public function visibility(string $path): string;

    /**
     * @param non-empty-string $path
     *
     * @return non-empty-string
     *
     * @throws FilesystemExceptionInterface
     */
    public function publicUrl(string $path, array $config = []): string;

    /**
     * @param non-empty-string $path
     *
     * @return non-empty-string
     *
     * @throws FilesystemExceptionInterface
     */
    public function temporaryUrl(string $path, DateTimeInterface $expiresAt, array $config = []): string;

    /**
     * @param non-empty-string $path
     *
     * @return non-empty-string
     *
     * @throws FilesystemExceptionInterface
     */
    public function checksum(string $path, array $config = []): string;
}
