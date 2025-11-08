<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Controllers;

use App\Application\UseCases\Subject\CreateSubject;
use App\Application\UseCases\Subject\DeleteSubject;
use App\Application\UseCases\Subject\EditSubject;
use App\Application\UseCases\Subject\GetPaginatedSubjects;
use App\Application\UseCases\Subject\ShowSubject;
use App\Domain\Exceptions\SubjectNotFoundException;
use App\Infrastructure\Framework\Requests\StoreSubject;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SubjectController extends Controller
{
    /**
     * @param GetPaginatedSubjects $useCase
     * @return JsonResponse
     */
    public function index(GetPaginatedSubjects $useCase): JsonResponse
    {
        return response()->json($useCase->execute()->toArray(), Response::HTTP_OK);
    }

    /**
     * @param StoreSubject $request
     * @param CreateSubject $createSubject
     * @return JsonResponse
     */
    public function store(StoreSubject $request, CreateSubject $createSubject): JsonResponse
    {
        $name = $request->validated('description');
        $createSubject->execute($name);

        return response()->json(null, Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @param StoreSubject $request
     * @param EditSubject $useCase
     * @return JsonResponse
     */
    public function update(int $id, StoreSubject $request, EditSubject $useCase): JsonResponse
    {
        $useCase->execute($id, $request->validated('description'));
        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @param DeleteSubject $useCase
     * @return JsonResponse
     */
    public function destroy(int $id, DeleteSubject $useCase): JsonResponse
    {
        $useCase->execute($id);
        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @param ShowSubject $useCase
     * @return JsonResponse
     * @throws SubjectNotFoundException
     */
    public function show(int $id, ShowSubject $useCase): JsonResponse
    {
        return response()->json($useCase->execute($id), Response::HTTP_OK);
    }
}
