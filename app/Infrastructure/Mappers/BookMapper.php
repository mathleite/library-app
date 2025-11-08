<?php

namespace App\Infrastructure\Mappers;

use App\Domain\Entities\Author;
use App\Domain\Entities\Book;
use App\Domain\Exceptions\InvalidMoneyException;
use App\Models\BookAuthorRelationModel;
use App\Models\BookModel;

readonly class BookMapper
{
    /**
     * @param BookModel $data
     * @return Book
     * @throws InvalidMoneyException
     */
    public static function toDomain(BookModel $data): Book
    {
        return Book::new(
            title: $data['Titulo'],
            editor: $data['Editora'],
            publicationYear: $data['AnoPublicacao'],
            edition: $data['Edicao'],
            price: $data['Preco'],
            id: $data['Codl'],
            authors: $data->authors
                ? self::mapAuthors($data->authors)
                : [],
        );
    }

    /**
     * @param iterable $authorRelation
     * @return array<Author>
     */
    private static function mapAuthors(iterable $authorRelation): array
    {
        return $authorRelation->map(function (BookAuthorRelationModel $relation) {
            return AuthorMapper::toDomain($relation->author);
        })->toArray();
    }
}
