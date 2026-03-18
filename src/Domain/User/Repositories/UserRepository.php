<?php

declare(strict_types=1);

namespace Src\Domain\User\Repositories;

use Src\Domain\User\Entities\User;

interface UserRepository
{
    public function save(User $user): User;

    public function findByEmail(string $email): ?User;
}
