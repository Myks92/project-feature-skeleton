<?php

declare(strict_types=1);

namespace App\Shared\Flusher\Test;

use App\Shared\Aggregate\AggregateRootInterface;
use App\Shared\Flusher\DoctrineTransactionWrapperFlusher;
use App\Shared\Flusher\FlusherInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(DoctrineTransactionWrapperFlusher::class)]
final class DoctrineTransactionWrapperFlusherTest extends TestCase
{
    public function testInterface(): void
    {
        $flusher = $this->createStub(FlusherInterface::class);
        $em = $this->createStub(EntityManagerInterface::class);
        $transactionFlusher = new DoctrineTransactionWrapperFlusher($em, $flusher);

        self::assertInstanceOf(FlusherInterface::class, $transactionFlusher);
    }

    public function testFlush(): void
    {
        $aggregateRoot = $this->createStub(AggregateRootInterface::class);

        $em = $this->createMock(EntityManager::class);
        $em->expects(self::once())->method('wrapInTransaction')->with(self::callback(static function (callable $func): bool {
            $func();
            return true;
        }));

        $flusher = $this->createMock(FlusherInterface::class);
        $flusher->expects(self::once())->method('flush')->with($aggregateRoot);

        $transactionFlusher = new DoctrineTransactionWrapperFlusher($em, $flusher);

        $transactionFlusher->flush($aggregateRoot);
    }

    public function testFlushWithOutCalls(): void
    {
        $flusher = $this->createMock(FlusherInterface::class);
        $flusher->expects(self::never())->method('flush');

        $em = $this->createMock(EntityManager::class);
        $em->expects(self::once())->method('wrapInTransaction');

        $transactionFlusher = new DoctrineTransactionWrapperFlusher($em, $flusher);

        $transactionFlusher->flush();
    }
}
