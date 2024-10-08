<?php

declare(strict_types=1);

namespace App\Http\Test\Normalizer;

use App\Http\Normalizer\DefaultJsonExceptionNormalizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(DefaultJsonExceptionNormalizer::class)]
final class DefaultJsonExceptionNormalizerTest extends TestCase
{
    public function testSuccessSupportsNormalization(): void
    {
        $translator = $this->createStub(TranslatorInterface::class);
        $normalizer = new DefaultJsonExceptionNormalizer($translator);

        $flattened = $this->createStub(FlattenException::class);

        self::assertTrue($normalizer->supportsNormalization($flattened, 'json'));
    }

    public function testNotFlattenExceptionSupportsNormalization(): void
    {
        $translator = $this->createStub(TranslatorInterface::class);
        $normalizer = new DefaultJsonExceptionNormalizer($translator);

        $flattened = $this->createStub(FlattenException::class);

        self::assertFalse($normalizer->supportsNormalization($flattened, 'html'));
    }

    public function testSuccessNormalize(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects(self::once())->method('trans')->with(
            self::equalTo('Some error'),
            self::equalTo([]),
            self::equalTo('exceptions'),
        )->willReturn('Ошибка');

        $normalizer = new DefaultJsonExceptionNormalizer($translator);

        $flattened = $this->createMock(FlattenException::class);
        $flattened->expects(self::once())->method('getStatusCode')->willReturn(400);
        $flattened->expects(self::once())->method('getMessage')->willReturn('Some error');

        $data = $normalizer->normalize($flattened, 'json');

        self::assertSame(['message' => 'Ошибка'], $data);
    }

    public function test404Normalize(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects(self::once())->method('trans')->with(
            self::equalTo('404 Not Found'),
            self::equalTo([]),
            self::equalTo('exceptions'),
        )->willReturn('404 Не найдено');

        $normalizer = new DefaultJsonExceptionNormalizer($translator);

        $flattened = $this->createMock(FlattenException::class);
        $flattened->expects(self::once())->method('getStatusCode')->willReturn(404);

        $data = $normalizer->normalize($flattened, 'json');

        self::assertSame(['message' => '404 Не найдено'], $data);
    }

    public function test500Normalize(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects(self::once())->method('trans')->with(
            self::equalTo('Internal Server Error'),
            self::equalTo([]),
            self::equalTo('exceptions'),
        )->willReturn('Внутренняя ошибка сервера');

        $normalizer = new DefaultJsonExceptionNormalizer($translator);

        $flattened = $this->createMock(FlattenException::class);
        $flattened->expects(self::once())->method('getStatusCode')->willReturn(500);

        $data = $normalizer->normalize($flattened, 'json');

        self::assertSame(['message' => 'Внутренняя ошибка сервера'], $data);
    }
}
