<?php

declare(strict_types=1);

namespace App\Application\UseCases\Author;

use App\Domain\Contracts\Persistence\AuthorRepository;
use App\Domain\Entities\Author;

readonly class CreateAuthor
{
    public function __construct(private AuthorRepository $repository)
    {
    }

    /**
     * @param string $name
     * @return void
     */
    public function execute(string $name): void
    {
        $this->repository->save(new Author($name));
    }
}
