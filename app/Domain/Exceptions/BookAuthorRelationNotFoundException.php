<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class BookAuthorRelationNotFoundException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct('Book author relation not found.', ['id' => $id]);
    }
}
