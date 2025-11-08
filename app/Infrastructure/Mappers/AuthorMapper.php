<?php

namespace App\Infrastructure\Mappers;

use App\Domain\Entities\Author;
use App\Models\AuthorModel;

readonly class AuthorMapper
{
    /**
     * @param AuthorModel $data
     * @return Author
     */
    public static function toDomain(AuthorModel $data): Author
    {
        return new Author(
            name: $data['Nome'],
            id: $data['CodAu']
        );
    }
}
