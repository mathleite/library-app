<?php

declare(strict_types=1);

namespace App\Application\UseCases\Subject;

use App\Domain\Contracts\Persistence\SubjectRepository;
use App\Domain\ValueObjects\CursorPagination;

readonly class GetPaginatedSubjects
{
    public function __construct(private SubjectRepository $repository)
    {
    }

    /**
     * @return CursorPagination
     */
    public function execute(): CursorPagination
    {
        return $this->repository->all();
    }
}
