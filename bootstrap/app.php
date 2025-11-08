<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (\App\Domain\Exceptions\DomainException $exception) {
            return response()->json(
                [
                'message' => $exception->getMessage(),
                'details' => $exception->details,
            ],
                isNotFoundResource($exception)
                ? Response::HTTP_NOT_FOUND
                : Response::HTTP_BAD_REQUEST
            );
        });
    })->create();

function isNotFoundResource(\App\Domain\Exceptions\DomainException $exception): bool
{
    return $exception instanceof \App\Domain\Exceptions\AuthorNotFoundException
        || $exception instanceof \App\Domain\Exceptions\BookNotFoundException
        || $exception instanceof \App\Domain\Exceptions\BookAuthorRelationNotFoundException;
}
