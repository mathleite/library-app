<?php

declare(strict_types=1);

namespace App\Application\UseCases\Book;

use App\Domain\Contracts\Persistence\BookRepository;
use App\Domain\Entities\Book;
use App\Domain\Exceptions\BookNotFoundException;
use App\Domain\Exceptions\InvalidMoneyException;

readonly class ShowBook
{
    public function __construct(private BookRepository $repository)
    {
    }

    /**
     * @param int $int
     * @return Book
     * @throws BookNotFoundException
     * @throws InvalidMoneyException
     */
    public function execute(int $int): Book
    {
        return $this->repository->findById($int);
    }
}
