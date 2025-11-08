<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class SubjectAlreadyExistsException extends DomainException
{
    public function __construct(string $authorName)
    {
        parent::__construct('Subject already exists.', ['name' => $authorName]);
    }
}
