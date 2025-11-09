<?php

namespace App\Application\Mappers;

use App\Domain\Entities\Author;
use App\Infrastructure\Framework\Persistence\Eloquent\Models\AuthorModel;

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
