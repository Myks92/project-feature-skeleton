<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer\Symfony\Test\Normalizer;

use App\Infrastructure\Serializer\Symfony\Normalizer\Denormalizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Context\ContextBuilderInterface;
use Symfony\Component\Serializer\Context\Normalizer\JsonSerializableNormalizerContextBuilder;
use Symfony\Component\Serializer\Context\SerializerContextBuilder;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Denormalizer::class)]
final class DenormalizerTest extends TestCase
{
    public function testValid(): void
    {
        $defaultContextBuilder = $this->createDefaultContextBuilderForDeserialize();

        $origin = $this->createMock(DenormalizerInterface::class);
        $origin->expects(self::once())->method('denormalize')
            ->with(
                ['name' => 'John'],
                \stdClass::class,
                null,
                $defaultContextBuilder->toArray(),
            )
            ->willReturn($object = new \stdClass());

        $denormalizer = new Denormalizer($origin);

        $result = $denormalizer->denormalize(['name' => 'John'], \stdClass::class);

        self::assertSame($object, $result);
    }

    public function testNotValid(): void
    {
        $origin = $this->createStub(DenormalizerInterface::class);
        $origin->method('denormalize')->willThrowException(
            $exception = new PartialDenormalizationException([], []),
        );

        $denormalizer = new Denormalizer($origin);

        $this->expectExceptionObject($exception);

        $denormalizer->denormalize(['name' => 42], \stdClass::class);
    }

    private function createDefaultContextBuilderForDeserialize(): ContextBuilderInterface
    {
        $contextBuilder = (new SerializerContextBuilder())
            ->withCollectDenormalizationErrors(true);

        return (new JsonSerializableNormalizerContextBuilder())
            ->withContext($contextBuilder)
            ->withAllowExtraAttributes(true);
    }
}
