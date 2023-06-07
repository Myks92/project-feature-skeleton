<?php

declare(strict_types=1);

namespace App\Infrastructure\Filesystem\Flysystem;

use App\Contracts\Filesystem\FilesystemInterface;
use App\Shared\Filesystem\DirectoryListing\DirectoryAttributes;
use App\Shared\Filesystem\DirectoryListing\DirectoryListing;
use App\Shared\Filesystem\DirectoryListing\FileAttributes;
use App\Shared\Filesystem\DirectoryListing\StorageAttributes;
use App\Shared\Filesystem\Exception\FilesystemException;
use DateTimeInterface;
use Generator;
use League\Flysystem\DirectoryAttributes as FlysystemDirectoryAttributes;
use League\Flysystem\FileAttributes as FlysystemFileAttributes;
use League\Flysystem\FilesystemException as FlysystemFilesystemException;
use League\Flysystem\FilesystemOperator;
use Throwable;

/**
 * @see https://flysystem.thephpleague.com/docs/usage/filesystem-api
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Filesystem implements FilesystemInterface
{
    public function __construct(
        private FilesystemOperator $defaultStorage,
    ) {
    }

    public function fileExists(string $path): bool
    {
        try {
            return $this->defaultStorage->fileExists($path);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function directoryExists(string $path): bool
    {
        try {
            return $this->defaultStorage->directoryExists($path);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function has(string $path): bool
    {
        try {
            return $this->defaultStorage->has($path);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function read(string $path): string
    {
        try {
            /** @var non-empty-string */
            return $this->defaultStorage->read($path);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function readStream(string $path)
    {
        try {
            return $this->defaultStorage->readStream($path);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function listContents(string $path, bool $deep = self::LIST_SHALLOW): DirectoryListing
    {
        try {
            $list = $this->defaultStorage->listContents($path, $deep);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }

        return new DirectoryListing($this->pipeListing($list));
    }

    public function lastModified(string $path): int
    {
        try {
            return $this->defaultStorage->lastModified($path);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function fileSize(string $path): int
    {
        try {
            return $this->defaultStorage->fileSize($path);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function mimeType(string $path): string
    {
        try {
            /** @var non-empty-string */
            return $this->defaultStorage->mimeType($path);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function visibility(string $path): string
    {
        try {
            /** @var non-empty-string */
            return $this->defaultStorage->visibility($path);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function publicUrl(string $path, array $config = []): string
    {
        try {
            /** @var non-empty-string */
            return $this->defaultStorage->publicUrl($path, $config);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function temporaryUrl(string $path, DateTimeInterface $expiresAt, array $config = []): string
    {
        try {
            /** @var non-empty-string */
            return $this->defaultStorage->temporaryUrl($path, $expiresAt, $config);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function checksum(string $path, array $config = []): string
    {
        try {
            /** @var non-empty-string */
            return $this->defaultStorage->checksum($path, $config);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function write(string $path, string $contents, array $config = []): void
    {
        try {
            $this->defaultStorage->write($path, $contents, $config);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function writeStream(string $path, mixed $contents, array $config = []): void
    {
        try {
            $this->defaultStorage->writeStream($path, $contents, $config);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function setVisibility(string $path, string $visibility): void
    {
        try {
            $this->defaultStorage->setVisibility($path, $visibility);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function delete(string $path): void
    {
        try {
            $this->defaultStorage->delete($path);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function deleteDirectory(string $path): void
    {
        try {
            $this->defaultStorage->deleteDirectory($path);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function createDirectory(string $path, array $config = []): void
    {
        try {
            $this->defaultStorage->createDirectory($path, $config);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function move(string $from, string $to, array $config = []): void
    {
        try {
            $this->defaultStorage->move($from, $to, $config);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    public function copy(string $from, string $to, array $config = []): void
    {
        try {
            $this->defaultStorage->move($from, $to, $config);
        } catch (FlysystemFilesystemException $e) {
            throw FilesystemException::fromThrowable($e);
        }
    }

    /**
     * @return Generator<mixed, StorageAttributes>
     */
    private function pipeListing(iterable $listing): Generator
    {
        try {
            /** @var \League\Flysystem\StorageAttributes $item */
            foreach ($listing as $item) {
                if ($item instanceof FlysystemFileAttributes) {
                    yield new FileAttributes(
                        path: $item->path(),
                        fileSize: $item->fileSize(),
                        visibility: $item->visibility(),
                        lastModified: $item->lastModified(),
                        mimeType: $item->mimeType(),
                        extraMetadata: $item->extraMetadata()
                    );
                }
                if ($item instanceof FlysystemDirectoryAttributes) {
                    yield new DirectoryAttributes(
                        path: $item->path(),
                        visibility: $item->visibility(),
                        lastModified: $item->lastModified(),
                        extraMetadata: $item->extraMetadata()
                    );
                }
            }
        } catch (Throwable $exception) {
            throw FilesystemException::fromThrowable($exception);
        }
    }
}
