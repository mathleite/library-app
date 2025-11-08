<?php

namespace App\Domain\Contracts\Persistence;

interface BookSubjectRelationRepository
{
    /**
     * @param int $bookId
     * @param int $subjectId
     * @return void
     */
    public function save(int $bookId, int $subjectId): void;

    /**
     * @param int $bookId
     * @param int $subjectId
     * @return void
     */
    public function update(int $bookId, int $subjectId): void;

    /**
     * @param int $bookId
     * @return void
     */
    public function delete(int $bookId): void;
}
