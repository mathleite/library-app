<?php

declare(strict_types=1);

namespace App\Application\UseCases\Book;

use App\Domain\Contracts\Persistence\BookAuthorRelationRepository;
use App\Domain\Contracts\Persistence\BookRepository;
use App\Domain\Contracts\Persistence\DatabaseTransactionManager;

readonly class DeleteBook
{
    public function __construct(
        private BookRepository $repository,
        private BookAuthorRelationRepository $relationRepository,
        private DatabaseTransactionManager $transactionManager
    ) {
    }

    /**
     * @param int $id
     * @return void
     */
    public function execute(int $id): void
    {
        $this->transactionManager->run(function () use ($id) {
            $this->repository->delete(id: $id);
            $this->relationRepository->delete(bookId: $id);
        });
    }
}
