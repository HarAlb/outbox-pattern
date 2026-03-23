<?php

declare(strict_types=1);

namespace Src\Shared\Contracts;

interface TransactionServiceInterface
{
    public function run(\Closure $callback): mixed;
}
