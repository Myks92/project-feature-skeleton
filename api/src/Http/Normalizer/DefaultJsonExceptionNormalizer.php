<?php

declare(strict_types=1);

namespace App\Http\Normalizer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class DefaultJsonExceptionNormalizer implements NormalizerInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {}

    #[\Override]
    public function getSupportedTypes(?string $format): array
    {
        return [
            FlattenException::class => true,
        ];
    }

    #[\Override]
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException && $format === 'json';
    }

    /**
     * @return array{message: string}
     */
    #[\Override]
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        if (!$object instanceof FlattenException) {
            throw new InvalidArgumentException(sprintf('The object must implement "%s".', FlattenException::class));
        }

        $message = match ($object->getStatusCode()) {
            404 => '404 Not Found',
            500 => 'Internal Server Error',
            default => $object->getMessage(),
        };

        return [
            'message' => $this->translator->trans($message, [], 'exceptions'),
        ];
    }
}
