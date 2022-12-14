<?php

declare(strict_types=1);

namespace App\Shared\ValueObject\Test;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Shared\ValueObject\Url
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class UrlTest extends TestCase
{
    public function testSuccess(): void
    {
        $url = new Url($value = 'http://site.ru');

        self::assertSame($value, $url->getValue());
    }

    public function testToString(): void
    {
        $url = new Url($value = 'http://site.ru');

        self::assertSame($value, (string)$url);
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Url('www.site.ru');
    }

    public function testEqual(): void
    {
        $url = new Url('http://site.ru');
        $url2 = new Url('http://site2.ru'); // other

        self::assertTrue($url->isEqual($url));
        self::assertFalse($url->isEqual($url2));
    }
}

/**
 * @psalm-immutable
 * @see \App\Shared\ValueObject\Test\UrlTest
 */
final class Url extends \App\Shared\ValueObject\Url
{
}
