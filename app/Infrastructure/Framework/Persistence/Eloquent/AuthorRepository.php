<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Persistence\Eloquent;

use App\Application\Mappers\AuthorMapper;
use App\Domain\Entities\Author;
use App\Domain\Exceptions\AuthorAlreadyExistsException;
use App\Domain\Exceptions\AuthorNotFoundException;
use App\Domain\ValueObjects\CursorPagination;
use App\Infrastructure\Framework\Persistence\Eloquent\Models\AuthorModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Pagination\CursorPaginator;

final readonly class AuthorRepository implements \App\Domain\Contracts\Persistence\AuthorRepository
{
    public function __construct(private AuthorModel $model)
    {
    }

    /**
     * @param Author $author
     * @return void
     * @throws AuthorAlreadyExistsException
     */
    public function save(Author $author): void
    {
        try {
            $this->model->create(['Nome' => $author->name]);
        } catch (UniqueConstraintViolationException) {
            throw new AuthorAlreadyExistsException(authorName: $author->name);
        }
    }

    /**
     * @return CursorPagination
     */
    public function all(): CursorPagination
    {
        /** @var CursorPaginator $paginate */
        $paginate = $this->model
            ->orderBy('Nome')
            ->cursorPaginate(10);

        return new CursorPagination(
            perPage: $paginate->perPage(),
            nextPageUrl: $paginate->nextPageUrl(),
            previousPageUrl: $paginate->previousPageUrl(),
            items: array_map(fn (AuthorModel $author) => AuthorMapper::toDomain($author), $paginate->items())
        );
    }

    /**
     * @param int $id
     * @param Author $author
     * @return void
     * @throws AuthorNotFoundException
     */
    public function update(int $id, Author $author): void
    {
        try {
            $this->model->findOrFail($id)
                ->update(['Nome' => $author->name]);
        } catch (ModelNotFoundException) {
            throw new AuthorNotFoundException(id: $id);
        }
    }

    /**
     * @param int $id
     * @return Author
     * @throws AuthorNotFoundException
     */
    public function findById(int $id): Author
    {
        try {
            $authorModel = $this->model->findOrFail($id);
            return AuthorMapper::toDomain($authorModel);
        } catch (ModelNotFoundException) {
            throw new AuthorNotFoundException(id: $id);
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws AuthorNotFoundException
     */
    public function delete(int $id): void
    {
        try {
            $this->model->findOrFail($id)
                ->delete();
        } catch (ModelNotFoundException) {
            throw new AuthorNotFoundException(id: $id);
        }
    }
}
