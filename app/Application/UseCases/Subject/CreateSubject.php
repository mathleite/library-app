<?php

declare(strict_types=1);

namespace App\Application\UseCases\Subject;

use App\Domain\Contracts\Persistence\SubjectRepository;
use App\Domain\Entities\Subject;

readonly class CreateSubject
{
    public function __construct(private SubjectRepository $repository)
    {
    }

    /**
     * @param string $description
     * @return void
     */
    public function execute(string $description): void
    {
        $this->repository->save(new Subject($description));
    }
}
