<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, User $other): bool
    {
        return $user->isAdmin() || $user->isSelf($other);
    }

    public function update(User $user, User $other): bool
    {
        return $user->isAdmin();
    }

    public function updatePassword(User $user, User $other): bool
    {
        return $user->isAdmin() || $user->isSelf($other);
    }

    public function ban(User $user, User $other): bool
    {
        return $user->isAdmin();
    }

}
