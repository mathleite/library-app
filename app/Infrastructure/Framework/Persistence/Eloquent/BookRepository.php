<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Persistence\Eloquent;

use App\Application\Mappers\BookMapper;
use App\Domain\Entities\Book;
use App\Domain\Exceptions\BookAlreadyExistsException;
use App\Domain\Exceptions\BookNotFoundException;
use App\Domain\Exceptions\InvalidMoneyException;
use App\Domain\ValueObjects\CursorPagination;
use App\Infrastructure\Framework\Persistence\Eloquent\Models\BookModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\CursorPaginator;

final readonly class BookRepository implements \App\Domain\Contracts\Persistence\BookRepository
{
    public function __construct(private BookModel $model)
    {
    }

    /**
     * @param Book $book
     * @return Book
     * @throws BookAlreadyExistsException|InvalidMoneyException
     */
    public function save(Book $book): Book
    {
        if ($bookData = $this->findByFields([
            'Titulo' => $book->title,
            'Editora' => $book->editor,
            'AnoPublicacao' => $book->publicationYear,
        ])) {
            throw new BookAlreadyExistsException($bookData->toArray());
        }

        $model = $this->model->create([
            'Titulo' => $book->title,
            'Editora' => $book->editor,
            'AnoPublicacao' => $book->publicationYear,
            'Edicao' => $book->edition,
            'Preco' => $book->price->amount,
        ]);
        return BookMapper::toDomain($model);
    }

    /**
     * @return CursorPagination
     * @throws InvalidMoneyException
     */
    public function all(): CursorPagination
    {
        /** @var CursorPaginator $paginate */
        $paginate = $this->model
            ->orderBy('Titulo')
            ->cursorPaginate(10);

        return new CursorPagination(
            perPage: $paginate->perPage(),
            nextPageUrl: $paginate->nextPageUrl(),
            previousPageUrl: $paginate->previousPageUrl(),
            items: array_map(fn (BookModel $book) => BookMapper::toDomain($book), $paginate->items())
        );
    }

    /**
     * @param int $id
     * @param Book $book
     * @return void
     * @throws BookNotFoundException
     * @throws InvalidMoneyException
     */
    public function update(int $id, Book $book): void
    {
        try {
            $this->model->findOrFail($id)
                ->update([
                    'Titulo' => $book->title,
                    'Editora' => $book->editor,
                    'AnoPublicacao' => $book->publicationYear,
                    'Edicao' => $book->edition,
                    'Preco' => $book->price->amount,
                ]);
        } catch (ModelNotFoundException) {
            throw new BookNotFoundException(id: $id);
        }
    }

    /**
     * @param int $id
     * @return Book
     * @throws BookNotFoundException
     * @throws InvalidMoneyException
     */
    public function findById(int $id): Book
    {
        try {
            $book = $this->model->findOrFail($id);
            return BookMapper::toDomain($book);
        } catch (ModelNotFoundException) {
            throw new BookNotFoundException(id: $id);
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws BookNotFoundException
     */
    public function delete(int $id): void
    {
        try {
            $this->model->findOrFail($id)
                ->delete();
        } catch (ModelNotFoundException) {
            throw new BookNotFoundException(id: $id);
        }
    }

    /**
     * @param array $fields
     * @return Book|null
     * @throws InvalidMoneyException
     */
    public function findByFields(array $fields): ?Book
    {
        $bookModel = $this->model->where($fields)->first();
        return $bookModel ? BookMapper::toDomain($bookModel) : null;
    }
}
