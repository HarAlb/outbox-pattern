<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use Src\Domain\Common\ValueObject\Id;
use Src\Domain\User\Entities\User;
use Src\Domain\User\Entities\ValueObject\Email;
use Src\Domain\User\Entities\ValueObject\Name;
use Src\Domain\User\Entities\ValueObject\Password;
use Src\Domain\User\Entities\ValueObject\Surname;
use Src\Domain\User\Repositories\UserRepository;

final class DatabaseUserRepository implements UserRepository
{
    #[\Override]
    public function save(User $user): User
    {
        $id = DB::table('users')->insertGetId([
            'name' => $user->getName()->value(),
            'email' => $user->getEmail()->value(),
            'surname' => $user->getSurname()?->value() ?? null,
            'password' => $user->getPassword()->value(),
        ]);

        return $user->setId(new Id($id));
    }

    #[\Override]
    public function findByEmail(Email $email): ?User
    {
        $row = DB::table('users')->where('email', $email->value())->first();

        if (! $row) {
            return null;
        }

        return (new User(new Name($row->name), new Email($row->email), Password::fromHash($row->password), $row->surname ? new Surname($row->surname) : null))->setId(new Id($row->id));
    }

    #[\Override]
    public function findById(Id $id): ?User
    {
        $row = DB::table('users')->where('id', $id->getValue())->first();

        if (! $row) {
            return null;
        }

        return (new User(new Name($row->name), new Email($row->email), Password::fromHash($row->password), $row->surname ? new Surname($row->surname) : null))->setId(new Id($row->id));
    }
}
