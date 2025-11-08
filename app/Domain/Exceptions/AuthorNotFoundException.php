<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class AuthorNotFoundException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct('Author not found.', ['id' => $id]);
    }
}
