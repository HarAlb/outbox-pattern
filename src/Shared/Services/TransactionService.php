<?php

declare(strict_types=1);

namespace Src\Shared\Services;

use Illuminate\Support\Facades\DB;
use Src\Shared\Contracts\TransactionServiceInterface;

final class TransactionService implements TransactionServiceInterface
{
    #[\Override]
    public function run(\Closure $callback): mixed
    {
        return DB::transaction($callback);
    }
}
