<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Test\Types;

use App\Infrastructure\Doctrine\Types\JsonUnescapedType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(JsonUnescapedType::class)]
final class JsonUnescapedTypeTest extends TestCase
{
    /** @var AbstractPlatform&MockObject */
    protected AbstractPlatform $platform;

    protected JsonUnescapedType $type;

    #[\Override]
    protected function setUp(): void
    {
        $this->platform = $this->createMock(AbstractPlatform::class);
        /** @psalm-suppress InternalMethod */
        $this->type = new JsonUnescapedType();
    }

    public function testPHPNullValueConvertsToJsonNull(): void
    {
        self::assertNull($this->type->convertToDatabaseValue(null, $this->platform));
    }

    public function testPHPValueConvertsToJsonString(): void
    {
        $source = ['параметр' => 'какой-то / текст', 'параметр 2' => 'другой текст!'];
        $databaseValue = $this->type->convertToDatabaseValue($source, $this->platform);
        self::assertSame('{"параметр":"какой-то \/ текст","параметр 2":"другой текст!"}', $databaseValue);
    }

    public function testPHPFloatValueConvertsToJsonString(): void
    {
        $source = ['параметр' => 11.4, 'параметр 2' => 10.0];
        $databaseValue = $this->type->convertToDatabaseValue($source, $this->platform);
        self::assertSame('{"параметр":11.4,"параметр 2":10.0}', $databaseValue);
    }

    public function testSerializationFailure(): void
    {
        $object = (object)[];
        $object->recursion = $object;
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage(
            'Could not convert PHP type \'stdClass\' to \'json\', as an \'Recursion detected\' error' .
            ' was triggered by the serialization',
        );
        $this->type->convertToDatabaseValue($object, $this->platform);
    }
}
