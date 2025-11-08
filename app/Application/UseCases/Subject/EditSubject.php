<?php

declare(strict_types=1);

namespace App\Application\UseCases\Subject;

use App\Domain\Contracts\Persistence\SubjectRepository;
use App\Domain\Entities\Subject;

readonly class EditSubject
{
    public function __construct(private SubjectRepository $repository)
    {
    }

    /**
     * @param int $id
     * @param string $description
     * @return void
     */
    public function execute(int $id, string $description): void
    {
        $this->repository->update($id, new Subject(description: $description));
    }
}
