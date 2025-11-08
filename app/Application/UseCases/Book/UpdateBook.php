<?php

declare(strict_types=1);

namespace App\Application\UseCases\Book;

use App\Domain\Contracts\Persistence\AuthorRepository;
use App\Domain\Contracts\Persistence\BookAuthorRelationRepository;
use App\Domain\Contracts\Persistence\BookRepository;
use App\Domain\Contracts\Persistence\BookSubjectRelationRepository;
use App\Domain\Contracts\Persistence\DatabaseTransactionManager;
use App\Domain\Contracts\Persistence\SubjectRepository;
use App\Domain\Entities\Book;
use App\Domain\Exceptions\AuthorNotFoundException;
use App\Domain\Exceptions\SubjectNotFoundException;

readonly class UpdateBook
{
    public function __construct(
        private BookRepository $repository,
        private AuthorRepository $authorRepository,
        private SubjectRepository $subjectRepository,
        private BookAuthorRelationRepository $relationRepository,
        private BookSubjectRelationRepository $subjectRelationRepository,
        private DatabaseTransactionManager $transactionManager
    ) {
    }

    /**
     * @param int $id
     * @param array $bookData
     * @return void
     * @throws AuthorNotFoundException|SubjectNotFoundException
     */
    public function execute(int $id, array $bookData): void
    {
        $this->validateAuthors($bookData['authors']);
        $this->validateSubjects($bookData['subjects']);

        $this->transactionManager->run(function () use ($id, $bookData) {
            $this->repository->update(id: $id, book: Book::new(
                title: $bookData['title'],
                editor: $bookData['editor'],
                publicationYear: $bookData['publication_year'],
                edition: $bookData['edition'],
                price: $bookData['price'],
            ));
            $this->saveAuthorRelation(bookId: $id, authors: $bookData['authors']);
            $this->saveSubjectRelation(bookId: $id, subjects: $bookData['subjects']);
            ;
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
     * @param array $subjects
     * @return void
     * @throws SubjectNotFoundException
     */
    private function validateSubjects(array $subjects): void
    {
        foreach ($subjects as $id) {
            $this->subjectRepository->findById($id);
        }
    }

    /**
     * @param int $bookId
     * @param array $authors
     * @return void
     */
    private function saveAuthorRelation(int $bookId, array $authors): void
    {
        $this->relationRepository->delete($bookId);
        foreach ($authors as $authorId) {
            $this->relationRepository->save($bookId, $authorId);
        }
    }

    /**
     * @param int $bookId
     * @param array $subjects
     * @return void
     */
    private function saveSubjectRelation(int $bookId, array $subjects): void
    {
        $this->subjectRelationRepository->delete($bookId);
        foreach ($subjects as $subjectId) {
            $this->subjectRelationRepository->save($bookId, $subjectId);
        }
    }
}
