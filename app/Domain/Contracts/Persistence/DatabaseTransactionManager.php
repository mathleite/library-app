<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Persistence;

interface DatabaseTransactionManager
{
    /**
     * @param callable $callback
     * @return mixed
     */
    public function run(callable $callback): mixed;
}
