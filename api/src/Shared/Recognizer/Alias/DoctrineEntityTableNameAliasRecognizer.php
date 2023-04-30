<?php

declare(strict_types=1);

namespace App\Shared\Recognizer\Alias;

use App\Shared\Recognizer\Alias\Exception\AliasNotRecognizedException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\MappingException;
use LogicException;

use function is_object;
use function is_string;
use function class_exists;

/**
 * @template-implements AliasRecognizerInterface<mixed>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class DoctrineEntityTableNameAliasRecognizer implements AliasRecognizerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function supports(mixed $data): bool
    {
        if (!is_object($data) && !is_string($data)) {
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
        if (is_object($data)) {
            return $data::class;
        }
        if (is_string($data)) {
            return $data;
        }
        throw new LogicException('Type not supported.');
    }
}
