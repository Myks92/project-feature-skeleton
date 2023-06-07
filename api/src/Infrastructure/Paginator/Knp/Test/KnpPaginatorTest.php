<?php

declare(strict_types=1);

namespace App\Infrastructure\Paginator\Knp\Test;

use App\Infrastructure\Paginator\Knp\KnpPaginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(KnpPaginator::class)]
final class KnpPaginatorTest extends TestCase
{
    public function testPaginate(): void
    {
        $target = [];

        $originPagination = $this->createMock(PaginationInterface::class);
        $originPagination->method('getItems')->willReturn([]);
        $originPagination->method('getTotalItemCount')->willReturn(10);
        $originPagination->method('getItemNumberPerPage')->willReturn(5);
        $originPagination->method('getCurrentPageNumber')->willReturn(1);
        $originPagination->method('getPaginatorOption')->willReturn('value');

        $origin = $this->createMock(PaginatorInterface::class);
        $origin->expects(self::once())->method('paginate')->with(
            self::equalTo($target),
            self::equalTo(1),
            self::equalTo(10),
            self::equalTo(['option' => 'value']),
        )->willReturn($originPagination);

        $paginator = new KnpPaginator($origin);

        $pagination = $paginator->paginate($target, 1, 10, ['option' => 'value']);

        self::assertSame($originPagination->getItems(), $pagination->getItems());
        self::assertSame($originPagination->getTotalItemCount(), $pagination->getTotalItemCount());
        self::assertSame($originPagination->getItemNumberPerPage(), $pagination->getItemNumberPerPage());
        self::assertSame($originPagination->getCurrentPageNumber(), $pagination->getCurrentPageNumber());
        self::assertSame($originPagination->getPaginatorOption('option'), 'value');
    }
}
