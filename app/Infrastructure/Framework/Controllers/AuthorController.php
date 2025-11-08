<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Controllers;

use App\Application\UseCases\Author\CreateAuthor;
use App\Application\UseCases\Author\DeleteAuthor;
use App\Application\UseCases\Author\EditAuthor;
use App\Application\UseCases\Author\GetPaginatedAuthors;
use App\Application\UseCases\Author\ShowAuthor;
use App\Domain\Exceptions\AuthorNotFoundException;
use App\Infrastructure\Framework\Requests\StoreAuthor;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    /**
     * @param GetPaginatedAuthors $useCase
     * @return JsonResponse
     */
    public function index(GetPaginatedAuthors $useCase): JsonResponse
    {
        return response()->json($useCase->execute()->toArray(), Response::HTTP_OK);
    }

    /**
     * @param StoreAuthor $request
     * @param CreateAuthor $createAuthor
     * @return JsonResponse
     */
    public function store(StoreAuthor $request, CreateAuthor $createAuthor): JsonResponse
    {
        $name = $request->validated('name');
        $createAuthor->execute($name);

        return response()->json(null, Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @param StoreAuthor $request
     * @param EditAuthor $useCase
     * @return JsonResponse
     */
    public function update(int $id, StoreAuthor $request, EditAuthor $useCase): JsonResponse
    {
        $useCase->execute($id, $request->validated('name'));
        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @param DeleteAuthor $useCase
     * @return JsonResponse
     */
    public function destroy(int $id, DeleteAuthor $useCase): JsonResponse
    {
        $useCase->execute($id);
        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @param ShowAuthor $useCase
     * @return JsonResponse
     * @throws AuthorNotFoundException
     */
    public function show(int $id, ShowAuthor $useCase): JsonResponse
    {
        return response()->json($useCase->execute($id), Response::HTTP_OK);
    }
}
