<?php

declare(strict_types=1);

namespace App\Application\UseCases\Subject;

use App\Domain\Contracts\Persistence\SubjectRepository;

readonly class DeleteSubject
{
    public function __construct(private SubjectRepository $repository)
    {
    }

    public function execute(int $id): void
    {
        $this->repository->delete($id);
    }
}
