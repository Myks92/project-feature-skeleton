<?php

declare(strict_types=1);

namespace App\Shared\Mailer\Test;

use App\Shared\Mailer\File;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(File::class)]
final class FileTest extends TestCase
{
    public function testFromContent(): void
    {
        $file1 = File::fromContent('');

        self::assertSame(32, \strlen($file1->getId()));
        self::assertSame($file1->getCid(), "cid:{$file1->getId()}");
        self::assertSame('', $file1->getContent());
        self::assertNull($file1->getContentType());
        self::assertNull($file1->getName());
        self::assertNull($file1->getPath());

        $file2 = File::fromContent('Content', 'text.txt', 'plain/text');

        self::assertNotSame($file1, $file2);

        self::assertSame(32, \strlen($file2->getId()));
        self::assertSame($file2->getCid(), "cid:{$file2->getId()}");
        self::assertSame('Content', $file2->getContent());
        self::assertSame('plain/text', $file2->getContentType());
        self::assertSame('text.txt', $file2->getName());
        self::assertNull($file2->getPath());
    }

    public function testFromPath(): void
    {
        $file1 = File::fromPath(__FILE__);

        self::assertSame(32, \strlen($file1->getId()));
        self::assertSame($file1->getCid(), "cid:{$file1->getId()}");
        self::assertSame(__FILE__, $file1->getPath());
        self::assertNull($file1->getContentType());
        self::assertNull($file1->getName());
        self::assertNull($file1->getContent());

        $file2 = File::fromPath(__FILE__, 'text.txt', 'plain/text');

        self::assertSame($file1->getPath(), $file2->getPath());
        self::assertNotSame($file1, $file2);

        self::assertSame(32, \strlen($file2->getId()));
        self::assertSame($file2->getCid(), "cid:{$file2->getId()}");
        self::assertSame('plain/text', $file2->getContentType());
        self::assertSame('text.txt', $file2->getName());
        self::assertNull($file2->getContent());
    }

    public function testFromPathThrowExceptionIfFileNotExist(): void
    {
        $this->expectException(RuntimeException::class);
        File::fromPath('file-not-exist');
    }

    public function testFromPathThrowExceptionIfFileIsDirectory(): void
    {
        $this->expectException(RuntimeException::class);
        File::fromPath(__DIR__);
    }
}
