<?php

declare(strict_types=1);

namespace App\Domain;

use App\ValueObjects\Exceptions\InvalidMoneyException;
use App\ValueObjects\Money;

readonly class Book
{
    /**
     * @param string $title
     * @param string $editor
     * @param string $publicationYear
     * @param int $edition
     * @param Money $price
     */
    public function __construct(
        string $title,
        string $editor,
        string $publicationYear,
        int $edition,
        Money $price
    ) {
    }

    /**
     * @param string $title
     * @param string $editor
     * @param string $publicationYear
     * @param int $edition
     * @param int $price
     * @return self
     * @throws InvalidMoneyException
     */
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
            price: new Money($price)
        );
    }
}
