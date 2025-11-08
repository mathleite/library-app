<?php

namespace App\Infrastructure\Framework\Controllers;

use App\Application\UseCases\Book\CreateBook;
use App\Application\UseCases\Book\DeleteBook;
use App\Application\UseCases\Book\GetPaginatedBooks;
use App\Application\UseCases\Book\ShowBook;
use App\Application\UseCases\Book\UpdateBook;
use App\Domain\Exceptions\AuthorNotFoundException;
use App\Domain\Exceptions\BookNotFoundException;
use App\Domain\Exceptions\InvalidMoneyException;
use App\Infrastructure\Framework\Requests\StoreBook;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    /**
     * @param GetPaginatedBooks $useCase
     * @return JsonResponse
     * @throws InvalidMoneyException
     */
    public function index(GetPaginatedBooks $useCase): JsonResponse
    {
        return response()->json($useCase->execute());
    }

    /**
     * @param StoreBook $request
     * @param CreateBook $useCase
     * @return JsonResponse
     * @throws AuthorNotFoundException
     */
    public function store(StoreBook $request, CreateBook $useCase): JsonResponse
    {
        $useCase->execute($request->validated());
        return response()->json(null, Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @param StoreBook $request
     * @param UpdateBook $useCase
     * @return JsonResponse
     * @throws AuthorNotFoundException
     */
    public function update(int $id, StoreBook $request, UpdateBook $useCase): JsonResponse
    {
        $useCase->execute($id, $request->validated());
        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @param ShowBook $useCase
     * @return JsonResponse
     * @throws InvalidMoneyException
     * @throws BookNotFoundException
     */
    public function show(int $id, ShowBook $useCase): JsonResponse
    {
        return response()->json($useCase->execute($id)->toArray(), Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @param DeleteBook $useCase
     * @return JsonResponse
     */
    public function destroy(int $id, DeleteBook $useCase): JsonResponse
    {
        $useCase->execute($id);
        return response()->json(null, Response::HTTP_OK);
    }
}
