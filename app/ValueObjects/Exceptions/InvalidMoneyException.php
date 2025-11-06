<?php

declare(strict_types=1);

namespace App\ValueObjects\Exceptions;

use App\Domain\DomainException;

class InvalidMoneyException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid money value provided.');
    }
}
