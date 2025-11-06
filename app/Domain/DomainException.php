<?php

declare(strict_types=1);

namespace App\Domain;

use Exception;

class DomainException extends Exception
{
    /**
     * @param string $message
     * @param array<string, mixed> $details
     */
    public function __construct(string $message, public readonly array $details = [])
    {
        parent::__construct($message);
    }
}
