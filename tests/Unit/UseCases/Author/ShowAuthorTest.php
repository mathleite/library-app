<?php

declare(strict_types=1);

use App\Application\UseCases\Author\ShowAuthor;
use App\Domain\Contracts\Persistence\AuthorRepository;
use App\Domain\Entities\Author;
use App\Domain\Exceptions\AuthorNotFoundException;
use Mockery as m;

it('should return the author returned by repository', function () {
    $repository = m::mock(AuthorRepository::class);
    $repository->shouldReceive('findById')
        ->once()
        ->with(1)
        ->andReturn(new Author('John Doe', 1));

    $useCase = new ShowAuthor($repository);

    $author = $useCase->execute(1);

    expect($author)
        ->toBeInstanceOf(Author::class)
        ->and($author->id)->toBe(1)
        ->and($author->name)->toBe('John Doe');
});

it('should throw AuthorNotFoundException when repository does not find an author', function () {
    $repository = m::mock(AuthorRepository::class);
    $repository->shouldReceive('findById')
        ->once()
        ->with(999)
        ->andThrow(new AuthorNotFoundException(999));

    $useCase = new ShowAuthor($repository);

    $useCase->execute(999);
})->throws(AuthorNotFoundException::class);
