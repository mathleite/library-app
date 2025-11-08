<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Shared\Arrayable;

readonly class Author
{
    use Arrayable;

    /**
     * @param string $name
     * @param int|null $id
     */
    public function __construct(
        public string $name,
        public ?int   $id = null
    ) {
    }
}
