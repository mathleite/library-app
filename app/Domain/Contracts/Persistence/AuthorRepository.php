<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Persistence;

use App\Domain\Entities\Author;
use App\Domain\Exceptions\AuthorNotFoundException;
use App\Domain\ValueObjects\CursorPagination;

interface AuthorRepository
{
    /**
     * @return CursorPagination
     */
    public function all(): CursorPagination;

    /**
     * @param Author $author
     * @return void
     */
    public function save(Author $author): void;

    /**
     * @param int $id
     * @param Author $author
     * @return void
     */
    public function update(int $id, Author $author): void;

    /**
     * @param int $id
     * @return Author
     * @throws AuthorNotFoundException
     */
    public function findById(int $id): Author;

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;
}
