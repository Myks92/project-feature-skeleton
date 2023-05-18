<?php

declare(strict_types=1);

namespace App\Shared\ValueObject\Test;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(\App\Shared\ValueObject\Email::class)]
final class EmailTest extends TestCase
{
    public function testSuccess(): void
    {
        $email = new Email($value = 'email@app.test');

        self::assertSame($value, $email->getValue());
    }

    public function testToString(): void
    {
        $email = new Email($value = 'email@app.test');

        self::assertSame($value, (string)$email);
    }

    public function testCase(): void
    {
        $email = new Email('EmAil@app.test');

        self::assertSame('email@app.test', $email->getValue());
    }

    public function testLocal(): void
    {
        $email = new Email('email@app.test');

        self::assertSame('email', $email->getLocal());
    }

    public function testDomain(): void
    {
        $email = new Email('email@app.test');

        self::assertSame('app.test', $email->getDomain());
    }

    public function testEqual(): void
    {
        $email = new Email('email@app.test');
        $email2 = new Email('email-other@app.test'); // other

        self::assertTrue($email->isEqual($email));
        self::assertFalse($email->isEqual($email2));
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Email('not-email');

        $this->expectException(InvalidArgumentException::class);
        new Email('email@app.test ');

        $this->expectException(InvalidArgumentException::class);
        new Email(' email@app.test');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        /** @psalm-suppress InvalidArgument */
        new Email('');
    }
}

/**
 * @see \App\Shared\ValueObject\Test\EmailTest
 */
final readonly class Email extends \App\Shared\ValueObject\Email
{
}
