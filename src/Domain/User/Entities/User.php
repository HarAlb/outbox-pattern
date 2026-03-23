<?php

declare(strict_types=1);

namespace Src\Domain\User\Entities;

use Src\Domain\Common\ValueObject\Id;
use Src\Domain\User\Entities\ValueObject\Email;
use Src\Domain\User\Entities\ValueObject\Name;
use Src\Domain\User\Entities\ValueObject\Password;
use Src\Domain\User\Entities\ValueObject\Surname;

final class User
{
    private Id $id;

    public function __construct(
        private Name $name,
        private Email $email,
        private Password $password,
        private ?Surname $surname = null,
    ) {}

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function changeEmail(Email $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function changePassword(Password $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setId(Id $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function changeName(Name $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function changeSurName(Surname $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getSurname(): ?Surname
    {
        return $this->surname;
    }
}
