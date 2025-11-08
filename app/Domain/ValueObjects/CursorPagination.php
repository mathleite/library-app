<?php

namespace App\Domain\ValueObjects;

use App\Domain\Shared\Arrayable;

readonly class CursorPagination
{
    use Arrayable;

    /**
     * @param int $perPage
     * @param string|null $nextPageUrl
     * @param string|null $previousPageUrl
     * @param mixed $items
     */
    public function __construct(
        public int     $perPage,
        public ?string $nextPageUrl = null,
        public ?string $previousPageUrl = null,
        public mixed   $items = [],
    ) {
    }
}
