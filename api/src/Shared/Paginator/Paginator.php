<?php

declare(strict_types=1);

namespace App\Shared\Paginator;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Paginator\Test\PaginatorTest
 */
final class Paginator implements PaginatorInterface
{
    public function __construct(
        private readonly \Knp\Component\Pager\PaginatorInterface $paginator
    ) {
    }

    public function paginate(mixed $target, int $page = 1, int $limit = null, array $options = []): PaginationInterface
    {
        $origin = $this->paginator->paginate($target, $page, $limit, $options);

        /** @psalm-var array $items */
        $items = $origin->getItems();

        $pagination = new Pagination();
        $pagination->setItems($items);
        $pagination->setCurrentPageNumber($origin->getCurrentPageNumber());
        $pagination->setItemNumberPerPage($origin->getItemNumberPerPage());
        $pagination->setTotalItemCount($origin->getTotalItemCount());
        $pagination->setPaginatorOptions($options);

        return $pagination;
    }
}
