<?php

declare(strict_types=1);

namespace App\Infrastructure\ValueObject\Test;

use App\Infrastructure\ValueObject\Email as SharedEmail;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(SharedEmail::class)]
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

        self::assertSame($value, (string) $email);
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

        self::assertTrue($email->equals($email));
        self::assertFalse($email->equals($email2));
    }

    public function testIncorrect(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Email('not-email');

        $this->expectException(\InvalidArgumentException::class);
        new Email('email@app.test ');

        $this->expectException(\InvalidArgumentException::class);
        new Email(' email@app.test');
    }

    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        /** @psalm-suppress InvalidArgument */
        new Email('');
    }
}

final readonly class Email extends SharedEmail {}
