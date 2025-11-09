<?php

namespace App\Application\Mappers;

use App\Domain\Entities\Subject;
use App\Infrastructure\Framework\Persistence\Eloquent\Models\SubjectModel;

readonly class SubjectMapper
{
    /**
     * @param SubjectModel $data
     * @return Subject
     */
    public static function toDomain(SubjectModel $data): Subject
    {
        return new Subject(
            description: $data['Descricao'],
            id: $data['codAs']
        );
    }
}
