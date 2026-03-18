<?php

declare(strict_types=1);

namespace Src\Domain\UserBonus\Repositories;

use Src\Domain\UserBonus\Entities\Bonus;

interface UserBonusesRepository
{
    public function add(Bonus $bonus): void;
}
