<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class AuthorAlreadyExistsException extends DomainException
{
    public function __construct(string $authorName)
    {
        parent::__construct('Author already exists.', ['name' => $authorName]);
    }
}
