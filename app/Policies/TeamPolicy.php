<?php

namespace App\Policies;

use App\Models\User;

class TeamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('teams.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('teams.manage');
    }

    public function update(User $user): bool
    {
        return $user->can('teams.manage');
    }

    public function delete(User $user): bool
    {
        return $user->can('teams.manage');
    }
}
