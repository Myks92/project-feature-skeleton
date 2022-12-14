<?php

declare(strict_types=1);

namespace App\Shared\Identifier\Test;

use App\Shared\Identifier\RamseyIdentifierGenerator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Shared\Identifier\RamseyIdentifierGenerator
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class RamseyIdentifierGeneratorTest extends TestCase
{
    public function testValid(): void
    {
        $generator = $this->createGenerator();
        $identifier = $generator->generate();

        self::assertTrue(Uuid::isValid($identifier));
    }

    public function testVersion(): void
    {
        $generator = $this->createGenerator();
        $identifier = $generator->generate();
        /** @var LazyUuidFromString $uuid */
        $uuid = Uuid::fromString($identifier);

        /** @psalm-suppress InternalMethod */
        self::assertSame(4, $uuid->getVersion());
    }

    private function createGenerator(): RamseyIdentifierGenerator
    {
        return new RamseyIdentifierGenerator();
    }
}
