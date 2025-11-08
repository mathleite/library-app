<?php

declare(strict_types=1);

namespace App\Application\UseCases\Author;

use App\Domain\Contracts\Persistence\AuthorRepository;
use App\Domain\Entities\Author;

readonly class EditAuthor
{
    public function __construct(private AuthorRepository $repository)
    {
    }

    /**
     * @param int $id
     * @param string $name
     * @return void
     */
    public function execute(int $id, string $name): void
    {
        $this->repository->update($id, new Author(name: $name));
    }
}
