<?php

namespace App\Application\UseCases\Book;

use App\Domain\Contracts\Persistence\BookRepository;
use App\Domain\Exceptions\InvalidMoneyException;
use App\Domain\ValueObjects\CursorPagination;

readonly class GetPaginatedBooks
{
    public function __construct(private BookRepository $repository)
    {
    }

    /**
     * @return CursorPagination
     * @throws InvalidMoneyException
     */
    public function execute(): CursorPagination
    {
        return $this->repository->all();
    }
}
