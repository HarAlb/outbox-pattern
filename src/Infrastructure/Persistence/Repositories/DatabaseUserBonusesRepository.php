<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Repositories;

use Illuminate\Support\Facades\DB;
use Src\Domain\UserBonus\Entities\Bonus;
use Src\Domain\UserBonus\Repositories\UserBonusesRepository;

final class DatabaseUserBonusesRepository implements UserBonusesRepository
{
    #[\Override]
    public function add(Bonus $bonus): void
    {
        DB::table('user_bonuses')->insert([
            'user_id' => $bonus->getUserId(),
            'amount' => $bonus->getAmount(),
            'created_at' => now(),
        ]);
    }
}
