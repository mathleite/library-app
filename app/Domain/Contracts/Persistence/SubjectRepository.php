<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Persistence;

use App\Domain\Entities\Subject;
use App\Domain\Exceptions\SubjectNotFoundException;
use App\Domain\ValueObjects\CursorPagination;

interface SubjectRepository
{
    /**
     * @return CursorPagination
     */
    public function all(): CursorPagination;

    /**
     * @param Subject $subject
     * @return void
     */
    public function save(Subject $subject): void;

    /**
     * @param int $id
     * @param Subject $subject
     * @return void
     */
    public function update(int $id, Subject $subject): void;

    /**
     * @param int $id
     * @return Subject
     * @throws SubjectNotFoundException
     */
    public function findById(int $id): Subject;

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;
}
