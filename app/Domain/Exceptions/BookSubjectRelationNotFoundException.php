<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class BookSubjectRelationNotFoundException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct('Book subject relation not found.', ['id' => $id]);
    }
}
