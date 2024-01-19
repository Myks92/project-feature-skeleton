<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Symfony\Test\Query;

use App\Contracts\Bus\Query\NotFoundException as ContractNotFoundException;
use App\Contracts\Bus\Query\QueryInterface;
use App\Infrastructure\Bus\Symfony\Query\QueryBus;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(QueryBus::class)]
final class QueryBusTest extends TestCase
{
    /**
     * @noRector ReturnNeverTypeRector
     */
    public function testSuccess(): void
    {
        $query = new class () implements QueryInterface {
            public string $id = '';
        };

        $envelope = $this->createMock(Envelope::class);
        $envelope->expects(self::once())->method('all')
            ->willReturn([new HandledStamp(['id' => '123'], '')]);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects(self::once())->method('dispatch')->with(
            self::equalTo($query),
            self::equalTo([]),
        )->willReturn($envelope);

        $queryBus = new QueryBus($messageBus);
        /** @var array $result */
        $result = $queryBus->dispatch($query);

        self::assertSame(['id' => '123'], $result);
    }

    public function testFailed(): void
    {
        $query = new class () implements QueryInterface {
            public string $id = '';
        };

        $failedException = $this->createMock(HandlerFailedException::class);
        $failedException->expects(self::once())->method('getWrappedExceptions')
            ->willReturn([new NotFoundException('Not found.')]);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects(self::once())->method('dispatch')->with(
            self::equalTo($query),
            self::equalTo([]),
        )->willThrowException($failedException);

        $queryBus = new QueryBus($messageBus);

        $this->expectException(NotFoundException::class);
        $queryBus->dispatch($query);
    }
}

final class NotFoundException extends \Exception implements ContractNotFoundException {}
