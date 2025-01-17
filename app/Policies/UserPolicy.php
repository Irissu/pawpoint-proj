<?php

namespace App\Policies;

use App\Models\User;
use Auth;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        return Auth::user()->isAdmin();
    }

    public function view(User $user): bool
    {
        return Auth::user()->isAdmin();
    }

    public function create(User $user): bool
    {
        return Auth::user()->isAdmin();
    }

    public function update(User $user): bool
    {
        return Auth::user()->isAdmin();
    }

    public function delete(User $user): bool
    {
        return Auth::user()->isAdmin();
    }
}
