<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class BookAlreadyExistsException extends DomainException
{
    public function __construct(array $bookData)
    {
        parent::__construct('Book already exists.', $bookData);
    }
}
