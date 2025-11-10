<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Persistence;

interface BookAuthorRelationRepository
{
    /**
     * @param int $bookId
     * @param int $authorId
     * @return void
     */
    public function save(int $bookId, int $authorId): void;

    /**
     * @param int $bookId
     * @param int $authorId
     * @return void
     */
    public function update(int $bookId, int $authorId): void;

    /**
     * @param int $bookId
     * @return void
     */
    public function delete(int $bookId): void;
}
