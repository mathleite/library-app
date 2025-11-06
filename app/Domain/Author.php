<?php

namespace App\Domain;

readonly class Author
{
    public function __construct(public string $name)
    {
    }
}
