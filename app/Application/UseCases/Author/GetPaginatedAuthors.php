<?php

declare(strict_types=1);

namespace App\Application\UseCases\Author;

use App\Domain\Contracts\Persistence\AuthorRepository;
use App\Domain\ValueObjects\CursorPagination;

readonly class GetPaginatedAuthors
{
    public function __construct(private AuthorRepository $repository)
    {
    }

    public function execute(): CursorPagination
    {
        return $this->repository->all();
    }
}
