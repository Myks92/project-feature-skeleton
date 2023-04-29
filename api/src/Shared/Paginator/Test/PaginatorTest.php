<?php

declare(strict_types=1);

namespace App\Shared\Paginator\Test;

use App\Shared\Paginator\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Paginator::class)]
final class PaginatorTest extends TestCase
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

        $paginator = new Paginator($origin);

        $pagination = $paginator->paginate($target, 1, 10, ['option' => 'value']);

        self::assertSame($originPagination->getItems(), $pagination->getItems());
        self::assertSame($originPagination->getTotalItemCount(), $pagination->getTotalItemCount());
        self::assertSame($originPagination->getItemNumberPerPage(), $pagination->getItemNumberPerPage());
        self::assertSame($originPagination->getCurrentPageNumber(), $pagination->getCurrentPageNumber());
        self::assertSame($originPagination->getPaginatorOption('option'), 'value');
    }
}
