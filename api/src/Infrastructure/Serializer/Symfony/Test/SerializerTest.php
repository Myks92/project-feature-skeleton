<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer\Symfony\Test;

use App\Infrastructure\Serializer\Symfony\Serializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Context\ContextBuilderInterface;
use Symfony\Component\Serializer\Context\Encoder\JsonEncoderContextBuilder;
use Symfony\Component\Serializer\Context\Normalizer\JsonSerializableNormalizerContextBuilder;
use Symfony\Component\Serializer\Context\SerializerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Serializer::class)]
final class SerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $data = ['name' => 'API'];

        $defaultContextBuilder = $this->createDefaultContextBuilderForSerialize();

        $origin = $this->createMock(SerializerInterface::class);
        $origin->expects(self::once())->method('serialize')->with(
            self::equalTo($data),
            'json',
            $defaultContextBuilder->toArray(),
        )->willReturn('{"name":"API"}');

        $serializer = new Serializer($origin);

        self::assertSame('{"name":"API"}', $serializer->serialize($data, 'json'));
    }

    public function testSerializeWithContext(): void
    {
        $data = ['name' => 'API'];

        $paramContextBuilder = (new SerializerContextBuilder())
            ->withEmptyArrayAsObject(true);

        $defaultContextBuilder = $this->createDefaultContextBuilderForSerialize()->withContext($paramContextBuilder);

        $origin = $this->createMock(SerializerInterface::class);
        $origin->expects(self::once())->method('serialize')->with(
            self::equalTo($data),
            'json',
            $defaultContextBuilder->toArray(),
        )->willReturn('{"name":"API"}');

        $serializer = new Serializer($origin);

        self::assertSame('{"name":"API"}', $serializer->serialize($data, 'json', $paramContextBuilder->toArray()));
    }

    public function testDeserialize(): void
    {
        $data = '{"name":"API"}';
        $result = new \stdClass();
        $result->name = 'API';

        $defaultContextBuilder = $this->createDefaultContextBuilderForDeserialize();

        $origin = $this->createMock(SerializerInterface::class);
        $origin->expects(self::once())->method('deserialize')->with(
            self::equalTo($data),
            \stdClass::class,
            'json',
            $defaultContextBuilder->toArray(),
        )->willReturn($result);

        $serializer = new Serializer($origin);

        self::assertSame($result, $serializer->deserialize($data, \stdClass::class, 'json'));
    }

    public function testDeserializeWithContext(): void
    {
        $data = '{"name":"API"}';
        $result = new \stdClass();
        $result->name = 'API';

        $paramsContextBuilder = (new SerializerContextBuilder())
            ->withEmptyArrayAsObject(true);

        $defaultContextBuilder = $this->createDefaultContextBuilderForDeserialize()->withContext($paramsContextBuilder);

        $origin = $this->createMock(SerializerInterface::class);
        $origin->expects(self::once())->method('deserialize')->with(
            self::equalTo($data),
            \stdClass::class,
            'json',
            $defaultContextBuilder->toArray(),
        )->willReturn($result);

        $serializer = new Serializer($origin);

        self::assertSame($result, $serializer->deserialize($data, \stdClass::class, 'json', $paramsContextBuilder->toArray()));
    }

    private function createDefaultContextBuilderForSerialize(): ContextBuilderInterface
    {
        return (new JsonEncoderContextBuilder())
            ->withEncodeOptions(JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    }

    private function createDefaultContextBuilderForDeserialize(): ContextBuilderInterface
    {
        $contextBuilder = (new SerializerContextBuilder())
            ->withCollectDenormalizationErrors(true);

        $contextBuilder = (new JsonSerializableNormalizerContextBuilder())
            ->withContext($contextBuilder)
            ->withAllowExtraAttributes(true);

        return (new JsonEncoderContextBuilder())
            ->withContext($contextBuilder)
            ->withDecodeOptions(JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    }
}
