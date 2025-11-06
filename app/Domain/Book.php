<?php

namespace App\Domain;

use App\ValueObjects\Amount;

readonly class Book
{
    public function __construct(
        string $title,
        string $editor,
        string $publicationYear,
        int $edition,
        Amount $price
    ) {
    }

    public static function new(
        string $title,
        string $editor,
        string $publicationYear,
        int $edition,
        int $price
    ): self {
        return new self(
            title: $title,
            editor: $editor,
            publicationYear: $publicationYear,
            edition: $edition,
            price: new Amount($price)
        );
    }
}
