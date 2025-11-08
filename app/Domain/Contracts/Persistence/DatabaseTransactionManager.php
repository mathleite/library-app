<?php

namespace App\Domain\Contracts\Persistence;

interface DatabaseTransactionManager
{
    /**
     * @param callable $callback
     * @return mixed
     */
    public function run(callable $callback): mixed;
}
