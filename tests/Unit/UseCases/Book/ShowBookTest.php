<?php

declare(strict_types=1);

use App\Application\UseCases\Book\ShowBook;
use App\Domain\Contracts\Persistence\BookRepository;
use App\Domain\Entities\Book;
use App\Domain\Exceptions\BookNotFoundException;
use App\Domain\Exceptions\InvalidMoneyException;
use Mockery as m;

it('should return the book returned by repository', function () {
    $repository = m::mock(BookRepository::class);

    $book = Book::new(
        title: 'Clean Code',
        editor: 'Prentice Hall',
        publicationYear: '2008',
        edition: 1,
        price: 12345,
        id: 10,
        authors: [],
        subjects: [],
    );

    $repository->shouldReceive('findById')
        ->once()
        ->with(10)
        ->andReturn($book);

    $useCase = new ShowBook($repository);

    $result = $useCase->execute(10);

    expect($result)
        ->toBeInstanceOf(Book::class)
        ->and($result->id)->toBe(10)
        ->and($result->title)->toBe('Clean Code');
});

it('should throw BookNotFoundException when repository does not find a book', function () {
    $repository = m::mock(BookRepository::class);
    $repository->shouldReceive('findById')
        ->once()
        ->with(404)
        ->andThrow(new BookNotFoundException(404));

    $useCase = new ShowBook($repository);

    $useCase->execute(\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
})->throws(BookNotFoundException::class);

it('should bubble InvalidMoneyException coming from repository if happens', function () {
    $repository = m::mock(BookRepository::class);
    $repository->shouldReceive('findById')
        ->once()
        ->with(1)
        ->andThrow(new InvalidMoneyException());

    $useCase = new ShowBook($repository);

    $useCase->execute(1);
})->throws(InvalidMoneyException::class);
