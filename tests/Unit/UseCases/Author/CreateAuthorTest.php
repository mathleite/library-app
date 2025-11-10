<?php

declare(strict_types=1);

use App\Application\UseCases\Author\CreateAuthor;
use App\Domain\Contracts\Persistence\AuthorRepository;
use App\Domain\Exceptions\AuthorAlreadyExistsException;
use Mockery as m;

beforeEach(function () {
    $this->repository = m::mock(AuthorRepository::class);
    $this->useCase = new CreateAuthor($this->repository);
});

it('should create author', function () {
    $this->repository->shouldReceive('save')
        ->once();

    (new CreateAuthor($this->repository))->execute('John Doe');

    expect(true)->toBeTrue();
});

it('should throw AuthorAlreadyExistsException when save', function () {
    $this->repository->shouldReceive('save')
        ->once()
        ->andThrow(new AuthorAlreadyExistsException('John Doe'));;

    (new CreateAuthor($this->repository))->execute('John Doe');

})->throws(AuthorAlreadyExistsException::class);

