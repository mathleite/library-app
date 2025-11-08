<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class SubjectNotFoundException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct('Subject not found.', ['id' => $id]);
    }
}
