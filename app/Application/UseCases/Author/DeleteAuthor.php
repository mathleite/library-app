<?php

declare(strict_types=1);

namespace App\Application\UseCases\Author;

use App\Domain\Contracts\Persistence\AuthorRepository;

readonly class DeleteAuthor
{
    public function __construct(private AuthorRepository $repository)
    {
    }

    public function execute(int $id): void
    {
        $this->repository->delete($id);
    }
}
