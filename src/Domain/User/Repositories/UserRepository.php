<?php

declare(strict_types=1);

namespace Src\Domain\User\Repositories;

use Src\Domain\Common\ValueObject\Id;
use Src\Domain\User\Entities\User;
use Src\Domain\User\Entities\ValueObject\Email;

interface UserRepository
{
    public function save(User $user): User;

    public function findByEmail(Email $email): ?User;

    public function findById(Id $id): ?User;
}
