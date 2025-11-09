<?php

namespace App\Infrastructure\Framework\Persistence\Eloquent;

use App\Domain\Exceptions\BookSubjectRelationNotFoundException;
use App\Infrastructure\Framework\Persistence\Eloquent\Models\BookSubjectRelationModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final readonly class BookSubjectRelationRepository implements \App\Domain\Contracts\Persistence\BookSubjectRelationRepository
{
    public function __construct(private BookSubjectRelationModel $model)
    {
    }

    /**
     * @param int $bookId
     * @param int $subjectId
     * @return void
     */
    public function save(int $bookId, int $subjectId): void
    {
        $this->model->create([
            'Livro_Codl' => $bookId,
            'Assunto_codAs' => $subjectId,
        ]);
    }

    /**
     * @param int $bookId
     * @param int $subjectId
     * @return void
     */
    public function update(int $bookId, int $subjectId): void
    {
        $this->model->findOrFail('Livro_Codl', $bookId)
            ->update(['Assunto_codAs' => $subjectId]);
    }

    /**
     * @param int $bookId
     * @return void
     * @throws BookSubjectRelationNotFoundException
     */
    public function delete(int $bookId): void
    {
        try {
            $this->model->where(['Livro_Codl' => $bookId])
                ->delete();
        } catch (ModelNotFoundException) {
            throw new BookSubjectRelationNotFoundException(id: $bookId);
        }
    }
}
