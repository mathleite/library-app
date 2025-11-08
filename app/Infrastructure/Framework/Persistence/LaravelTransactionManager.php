<?php

namespace App\Infrastructure\Framework\Persistence;

use App\Domain\Contracts\Persistence\DatabaseTransactionManager;
use Illuminate\Support\Facades\DB;

final class LaravelTransactionManager implements DatabaseTransactionManager
{
    /**
     * @param callable $callback
     * @return mixed
     */
    public function run(callable $callback): mixed
    {
        return DB::transaction($callback);
    }
}
