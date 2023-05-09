<?php

declare(strict_types=1);

namespace App\Shared\Filesystem;

use App\Shared\Filesystem\Exception\FilesystemExceptionInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface FilesystemWriterInterface
{
    /**
     * @param non-empty-string $path
     * @param non-empty-string $contents
     *
     * @throws FilesystemExceptionInterface
     */
    public function write(string $path, string $contents, array $config = []): void;

    /**
     * @param non-empty-string $path
     *
     * @throws FilesystemExceptionInterface
     */
    public function writeStream(string $path, mixed $contents, array $config = []): void;

    /**
     * @param non-empty-string $path
     * @param non-empty-string $visibility
     *
     * @throws FilesystemExceptionInterface
     */
    public function setVisibility(string $path, string $visibility): void;

    /**
     * @param non-empty-string $path
     *
     * @throws FilesystemExceptionInterface
     */
    public function delete(string $path): void;

    /**
     * @param non-empty-string $path
     *
     * @throws FilesystemExceptionInterface
     */
    public function deleteDirectory(string $path): void;

    /**
     * @param non-empty-string $path
     *
     * @throws FilesystemExceptionInterface
     */
    public function createDirectory(string $path, array $config = []): void;

    /**
     * @param non-empty-string $from
     * @param non-empty-string $to
     *
     * @throws FilesystemExceptionInterface
     */
    public function move(string $from, string $to, array $config = []): void;

    /**
     * @param non-empty-string $from
     * @param non-empty-string $to
     *
     * @throws FilesystemExceptionInterface
     */
    public function copy(string $from, string $to, array $config = []): void;
}
