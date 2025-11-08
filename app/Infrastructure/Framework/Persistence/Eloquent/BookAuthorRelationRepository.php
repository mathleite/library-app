<?php

namespace App\Infrastructure\Framework\Persistence\Eloquent;

use App\Domain\Exceptions\BookAuthorRelationNotFoundException;
use App\Models\BookAuthorRelationModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final readonly class BookAuthorRelationRepository implements \App\Domain\Contracts\Persistence\BookAuthorRelationRepository
{
    public function __construct(private BookAuthorRelationModel $model)
    {
    }

    /**
     * @param int $bookId
     * @param int $authorId
     * @return void
     */
    public function save(int $bookId, int $authorId): void
    {
        $this->model->create([
            'Livro_Codl' => $bookId,
            'Autor_CodAu' => $authorId,
        ]);
    }

    /**
     * @param int $bookId
     * @param int $authorId
     * @return void
     */
    public function update(int $bookId, int $authorId): void
    {
        $this->model->findOrFail('Livro_Codl', $bookId)
            ->update(['Autor_CodAu' => $authorId]);
    }

    /**
     * @param int $bookId
     * @return void
     * @throws BookAuthorRelationNotFoundException
     */
    public function delete(int $bookId): void
    {
        try {
            $this->model->where(['Livro_Codl' => $bookId])
                ->delete();
        } catch (ModelNotFoundException) {
            throw new BookAuthorRelationNotFoundException(id: $bookId);
        }
    }
}
