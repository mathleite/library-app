<?php

declare(strict_types=1);

namespace App\Domain;

readonly class Author
{
    /** @param string $name */
    public function __construct(public string $name)
    {
    }
}
