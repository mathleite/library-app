<?php

declare(strict_types=1);

namespace App\Application\UseCases\Author;

use App\Domain\Contracts\Persistence\AuthorRepository;
use App\Domain\Entities\Author;
use App\Domain\Exceptions\AuthorNotFoundException;

readonly class ShowAuthor
{
    public function __construct(private AuthorRepository $repository)
    {
    }

    /**
     * @param int $id
     * @return Author
     * @throws AuthorNotFoundException
     */
    public function execute(int $id): Author
    {
        return $this->repository->findById($id);
    }
}
