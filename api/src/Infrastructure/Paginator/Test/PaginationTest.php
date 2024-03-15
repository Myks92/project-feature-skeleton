<?php

declare(strict_types=1);

namespace App\Infrastructure\Paginator\Test;

use App\Infrastructure\Paginator\Pagination;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Pagination::class)]
final class PaginationTest extends TestCase
{
    public function testSuccess(): void
    {
        $paginate = new Pagination();
        $paginate->setItems([['id' => 1], ['id' => 2]]);
        $paginate->offsetSet(2, ['id' => 3]);
        $paginate->setTotalItemCount(3);
        $paginate->setItemNumberPerPage(3);
        $paginate->setCurrentPageNumber(1);
        $paginate->setCustomParameters(['param_name' => 'param_value']);
        $paginate->setPaginatorOptions(['option_name' => 'option_value']);
        $paginate->rewind();

        self::assertSame(['id' => 1], $paginate->current());
        self::assertSame(0, $paginate->key());
        self::assertTrue($paginate->valid());
        self::assertSame(3, $paginate->count());
        self::assertSame([['id' => 1], ['id' => 2], ['id' => 3]], $paginate->getItems());
        self::assertSame('param_value', $paginate->getCustomParameter('param_name'));
        self::assertSame(1, $paginate->getCurrentPageNumber());
        self::assertSame(3, $paginate->getItemNumberPerPage());
        self::assertSame(3, $paginate->getTotalItemCount());
        self::assertSame('option_value', $paginate->getPaginatorOption('option_name'));
        self::assertTrue($paginate->offsetExists(0));
        self::assertSame(['id' => 1], $paginate->offsetGet(0));
        self::assertSame(['id' => 3], $paginate->offsetGet(2));

        $paginate->next();
        self::assertSame(['id' => 2], $paginate->current());
        self::assertSame(1, $paginate->key());

        $paginate->rewind();
        self::assertSame(['id' => 1], $paginate->current());
        self::assertSame(0, $paginate->key());
    }
}
