<?php

declare(strict_types=1);

namespace Src\Domain\UserBonus\Listeners;

use Src\Domain\User\Events\UserRegistered;
use Src\Domain\UserBonus\Entities\Bonus;
use Src\Domain\UserBonus\Repositories\UserBonusesRepository;

final class GiveRegistrationBonusListener
{
    public function __construct(private UserBonusesRepository $repository) {}

    public function handle(UserRegistered $event): void
    {
        $bonus = new Bonus($event->getUserId()->getValue(), 100);

        $this->repository->add($bonus);
    }
}
