<?php

declare(strict_types=1);

namespace Src\Application\Auth\Contracts;

use Src\Application\Auth\Responses\TokenResponse;
use Src\Domain\User\Entities\User;

interface TokenServiceInterface
{
    public function generate(User $user): TokenResponse;
}
