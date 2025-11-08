<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class BookNotFoundException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct('Book not found.', ['id' => $id]);
    }
}
