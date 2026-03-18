<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use Src\Domain\User\Entities\User;
use Src\Domain\User\Repositories\UserRepository;

final class DatabaseUserRepository implements UserRepository
{
    #[\Override]
    public function save(User $user): User
    {
        $id = DB::table('users')->insertGetId([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'surname' => $user->getSurname(),
            'password' => $user->getPassword(),
        ]);

        return $user->setId($id);
    }

    #[\Override]
    public function findByEmail(string $email): ?User
    {
        $row = DB::table('users')->where('email', $email)->first();

        if (! $row) {
            return null;
        }

        return (new User($row->name, $row->email, $row->password, $row->surname))->setId($row->id);
    }
}
