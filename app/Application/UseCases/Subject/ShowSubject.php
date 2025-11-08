<?php

declare(strict_types=1);

namespace App\Application\UseCases\Subject;

use App\Domain\Contracts\Persistence\SubjectRepository;
use App\Domain\Entities\Subject;
use App\Domain\Exceptions\SubjectNotFoundException;

readonly class ShowSubject
{
    public function __construct(private SubjectRepository $repository)
    {
    }

    /**
     * @param int $id
     * @return Subject
     * @throws SubjectNotFoundException
     */
    public function execute(int $id): Subject
    {
        return $this->repository->findById($id);
    }
}
