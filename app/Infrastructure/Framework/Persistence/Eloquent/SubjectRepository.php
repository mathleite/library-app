<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Persistence\Eloquent;

use App\Application\Mappers\SubjectMapper;
use App\Domain\Entities\Subject;
use App\Domain\Exceptions\SubjectAlreadyExistsException;
use App\Domain\Exceptions\SubjectNotFoundException;
use App\Domain\ValueObjects\CursorPagination;
use App\Infrastructure\Framework\Persistence\Eloquent\Models\SubjectModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Pagination\CursorPaginator;

final readonly class SubjectRepository implements \App\Domain\Contracts\Persistence\SubjectRepository
{
    public function __construct(private SubjectModel $model)
    {
    }

    /**
     * @param Subject $subject
     * @return void
     * @throws SubjectAlreadyExistsException
     */
    public function save(Subject $subject): void
    {
        try {
            $this->model->create(['Descricao' => $subject->description]);
        } catch (UniqueConstraintViolationException) {
            throw new SubjectAlreadyExistsException(authorName: $subject->description);
        }
    }

    /**
     * @return CursorPagination
     */
    public function all(): CursorPagination
    {
        /** @var CursorPaginator $paginate */
        $paginate = $this->model
            ->orderBy('Descricao')
            ->cursorPaginate(10);

        return new CursorPagination(
            perPage: $paginate->perPage(),
            nextPageUrl: $paginate->nextPageUrl(),
            previousPageUrl: $paginate->previousPageUrl(),
            items: array_map(fn (SubjectModel $subject) => SubjectMapper::toDomain($subject), $paginate->items())
        );
    }

    /**
     * @param int $id
     * @param Subject $subject
     * @return void
     * @throws SubjectNotFoundException
     */
    public function update(int $id, Subject $subject): void
    {
        try {
            $this->model->findOrFail($id)
                ->update(['Descricao' => $subject->description]);
        } catch (ModelNotFoundException) {
            throw new SubjectNotFoundException(id: $id);
        }
    }

    /**
     * @param int $id
     * @return Subject
     * @throws SubjectNotFoundException
     */
    public function findById(int $id): Subject
    {
        try {
            /** @var SubjectModel $subjectModel */
            $subjectModel = $this->model->findOrFail($id);
            return SubjectMapper::toDomain($subjectModel);
        } catch (ModelNotFoundException) {
            throw new SubjectNotFoundException(id: $id);
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws SubjectNotFoundException
     */
    public function delete(int $id): void
    {
        try {
            $this->model->findOrFail($id)
                ->delete();
        } catch (ModelNotFoundException) {
            throw new SubjectNotFoundException(id: $id);
        }
    }
}
