<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Exceptions\InvalidMoneyException;
use App\Domain\Shared\Arrayable;
use App\Domain\ValueObjects\Money;

readonly class Book
{
    use Arrayable;

    /**
     * @param string $title
     * @param string $editor
     * @param string $publicationYear
     * @param int $edition
     * @param Money $price
     * @param int|null $id
     * @param array $authors
     */
    public function __construct(
        public string $title,
        public string $editor,
        public string $publicationYear,
        public int $edition,
        public Money $price,
        public ?int $id = null,
        public array $authors = [],
        public array $subjects = [],
    ) {
    }

    /**
     * @param string $title
     * @param string $editor
     * @param string $publicationYear
     * @param int $edition
     * @param int $price
     * @param int|null $id
     * @param array $authors
     * @param array $subjects
     * @return self
     * @throws InvalidMoneyException
     */
    public static function new(
        string $title,
        string $editor,
        string $publicationYear,
        int $edition,
        int $price,
        ?int $id = null,
        array $authors = [],
        array $subjects = [],
    ): self {
        return new self(
            title: $title,
            editor: $editor,
            publicationYear: $publicationYear,
            edition: $edition,
            price: new Money($price),
            id: $id,
            authors: $authors,
            subjects: $subjects,
        );
    }
}
