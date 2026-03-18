<?php

declare(strict_types=1);

namespace Src\Shared\Services;

use Illuminate\Support\Facades\DB;

final class TransactionService
{
    public function run(\Closure $callback)
    {
        return DB::transaction($callback);
    }
}
