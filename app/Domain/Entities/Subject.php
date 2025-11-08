<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Shared\Arrayable;

readonly class Subject
{
    use Arrayable;

    /**
     * @param string $description
     * @param int|null $id
     */
    public function __construct(
        public string $description,
        public ?int $id = null
    ) {
    }
}
