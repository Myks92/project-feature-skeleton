<?php

declare(strict_types=1);

namespace App\Infrastructure\Recognizer\Alias;

use App\Contracts\Recognizer\Alias\AliasRecognizerInterface;
use App\Infrastructure\Recognizer\Alias\Exception\AliasNotRecognizedException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\MappingException;

/**
 * @template T
 * @template-implements AliasRecognizerInterface<T>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class DoctrineEntityTableNameAliasRecognizer implements AliasRecognizerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    #[\Override]
    public function supports(mixed $data): bool
    {
        if (!\is_object($data) && !\is_string($data)) {
            return false;
        }

        $className = $this->getClassName($data);

        if (!class_exists($className)) {
            return false;
        }

        try {
            $this->em->getClassMetadata($className)->getTableName();

            return true;
        } catch (MappingException) {
            return false;
        }
    }

    #[\Override]
    public function recognize(mixed $data): string
    {
        if (!$this->supports($data)) {
            throw new AliasNotRecognizedException();
        }
        $className = $this->getClassName($data);

        return $this->em->getClassMetadata($className)->getTableName();
    }

    private function getClassName(mixed $data): string
    {
        if (\is_object($data)) {
            return $data::class;
        }
        if (\is_string($data)) {
            return $data;
        }

        throw new \LogicException('Type not supported.');
    }
}
