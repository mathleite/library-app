<?php

declare(strict_types=1);

namespace App\Application\UseCases\Book;

use App\Domain\Contracts\Persistence\AuthorRepository;
use App\Domain\Contracts\Persistence\BookAuthorRelationRepository;
use App\Domain\Contracts\Persistence\BookRepository;
use App\Domain\Contracts\Persistence\DatabaseTransactionManager;
use App\Domain\Entities\Book;
use App\Domain\Exceptions\AuthorNotFoundException;

readonly class CreateBook
{
    public function __construct(
        private BookRepository $repository,
        private AuthorRepository $authorRepository,
        private BookAuthorRelationRepository $relationRepository,
        private DatabaseTransactionManager $transactionManager
    ) {
    }

    /**
     * @param array $bookData
     * @return void
     * @throws AuthorNotFoundException
     */
    public function execute(array $bookData): void
    {
        $this->validateAuthors($bookData['authors']);
        $this->transactionManager->run(function () use ($bookData) {
            $book = $this->repository->save(Book::new(
                title: $bookData['title'],
                editor: $bookData['editor'],
                publicationYear: $bookData['publication_year'],
                edition: $bookData['edition'],
                price: $bookData['price'],
            ));
            $this->saveRelation(bookId: $book->id, authors: $bookData['authors']);
        });
    }

    /**
     * @param array $authors
     * @return void
     * @throws AuthorNotFoundException
     */
    private function validateAuthors(array $authors): void
    {
        foreach ($authors as $id) {
            $this->authorRepository->findById($id);
        }
    }

    /**
     * @param int $bookId
     * @param array $authors
     * @return void
     */
    private function saveRelation(int $bookId, array $authors): void
    {
        foreach ($authors as $authorId) {
            $this->relationRepository->save($bookId, $authorId);
        }
    }
}
