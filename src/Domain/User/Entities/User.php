<?php

declare(strict_types=1);

namespace Src\Domain\User\Entities;

final class User
{
    private int $id;

    public function __construct(
        private string $name,
        private string $email,
        private string $password,
        private ?string $surname = null,
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function changeEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function changePassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function changeName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function changeSurName(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }
}
