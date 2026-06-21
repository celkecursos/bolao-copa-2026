<?php

namespace App\Policies;

use App\Models\User;

class GamePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('games.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('games.manage');
    }

    public function update(User $user): bool
    {
        return $user->can('games.manage');
    }

    public function delete(User $user): bool
    {
        return $user->can('games.manage');
    }

    public function setResult(User $user): bool
    {
        return $user->can('games.set-result');
    }
}
