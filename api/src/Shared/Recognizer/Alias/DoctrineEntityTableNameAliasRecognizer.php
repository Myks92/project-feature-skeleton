<?php

declare(strict_types=1);

namespace App\Shared\Recognizer\Alias;

use App\Shared\Recognizer\Alias\Exception\AliasNotRecognizedException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\MappingException;

/**
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
        $className = $this->getClassName($data);

        if ($className === null) {
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

    /**
     * @return class-string|null
     */
    private function getClassName(mixed $data): ?string
    {
        $className = null;
        if (\is_object($data) && class_exists($data::class)) {
            $className = $data::class;
        }
        if (class_exists($data)) {
            $className = $data;
        }
        return $className;
    }
}
