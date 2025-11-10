<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Persistence;

use App\Domain\Entities\Book;
use App\Domain\Exceptions\BookAlreadyExistsException;
use App\Domain\Exceptions\BookNotFoundException;
use App\Domain\Exceptions\InvalidMoneyException;
use App\Domain\ValueObjects\CursorPagination;

interface BookRepository
{
    /**
     * @return CursorPagination
     * @throws InvalidMoneyException
     */
    public function all(): CursorPagination;

    /**
     * @param Book $book
     * @return Book
     * @throws BookAlreadyExistsException|InvalidMoneyException
     */
    public function save(Book $book): Book;

    /**
     * @param int $id
     * @param Book $book
     * @return void
     */
    public function update(int $id, Book $book): void;

    /**
     * @param int $id
     * @return Book
     * @throws BookNotFoundException
     * @throws InvalidMoneyException
     */
    public function findById(int $id): Book;

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;
}
