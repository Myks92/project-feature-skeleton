<?php

declare(strict_types=1);

namespace App\Shared\Doctrine\Test\Types;

use App\Shared\Doctrine\Types\AbstractUuidType;
use App\Shared\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(AbstractUuidType::class)]
final class AbstractUuidTypeTest extends TestCase
{
    /** @var AbstractPlatform&MockObject */
    protected AbstractPlatform $platform;
    protected AbstractUuidType $type;

    protected function setUp(): void
    {
        $this->platform = $this->createMock(AbstractPlatform::class);
        /** @psalm-suppress InternalMethod */
        $this->type = new class() extends AbstractUuidType {
            protected const NAME = 'test_uuid';

            protected function getClassName(): string
            {
                return UuidTest::class;
            }

            public function getName(): string
            {
                return self::NAME;
            }
        };
    }

    public function testPHPNullValueConvertsToNull(): void
    {
        self::assertNull($this->type->convertToDatabaseValue(null, $this->platform));
    }

    public function testPHPUuidConvertsToString(): void
    {
        $value = new UuidTest('00000000-0000-0000-0000-000000000000');
        $databaseValue = $this->type->convertToDatabaseValue($value, $this->platform);
        self::assertSame('00000000-0000-0000-0000-000000000000', $databaseValue);
    }

    public function testPHPNotUuidConvertsToStringFailed(): void
    {
        $value = new stdClass();
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage(
            'Could not convert PHP value of type stdClass to type test_uuid. Expected one of the following types: test_uuid'
        );
        $this->type->convertToDatabaseValue($value, $this->platform);
    }

    public function testPHPValueConvertsToStringFailed(): void
    {
        $value = '00000000-0000-0000-0000-000000000000';
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage(
            'Could not convert PHP value \'00000000-0000-0000-0000-000000000000\' to type test_uuid. Expected one of the following types: test_uuid'
        );
        $this->type->convertToDatabaseValue($value, $this->platform);
    }

    public function testDatabaseNullConvertsToNull(): void
    {
        self::assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    public function testDatabaseUuidConvertsToUuid(): void
    {
        $value = new UuidTest('00000000-0000-0000-0000-000000000000');
        $phpValue = $this->type->convertToPHPValue($value, $this->platform);
        self::assertSame($value, $phpValue);
    }

    public function testDatabaseStringConvertsToUuid(): void
    {
        $value = '00000000-0000-0000-0000-000000000000';
        $phpValue = $this->type->convertToPHPValue($value, $this->platform);
        self::assertNotNull($phpValue);
        self::assertSame($value, $phpValue->getValue());
    }

    public function testDatabaseStringConvertsToUuidNotUuid(): void
    {
        $value = '12345';
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage(
            'Could not convert database value "12345" to Doctrine Type '
        );
        $this->type->convertToPHPValue($value, $this->platform);
    }
}

/**
 * @internal
 */
final readonly class UuidTest extends Uuid
{
}
