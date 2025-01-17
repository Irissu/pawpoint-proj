<?php

namespace App\Policies;

use App\Models\User;
use Auth;

class SlotPolicy
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
        if (Auth::user()->isAdmin() || Auth::user()->isVet()) {
            return true;
        } else {
            return false;
        }
    }

    public function view(User $user): bool
    {
        if (Auth::user()->isAdmin() || Auth::user()->isVet()) {
            return true;
        } else {
            return false;
        }
    }

    public function create(User $user): bool
    {
        if (Auth::user()->isAdmin() || Auth::user()->isVet()) {
            return true;
        } else {
            return false;
        }
    }

    public function update(User $user): bool
    {
        if (Auth::user()->isAdmin() || Auth::user()->isVet()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $user): bool
    {
        if (Auth::user()->isAdmin() || Auth::user()->isVet()) {
            return true;
        } else {
            return false;
        }
    }

    public function edit(User $user): bool
    {
        if (Auth::user()->isAdmin() || Auth::user()->isVet()) {
            return true;
        } else {
            return false;
        }
    }

    public function restore(User $user): bool
    {
        return true;
    }

    public function forceDelete(User $user): bool
    {
        if (Auth::user()->isAdmin()) {
            return true;
        } else {
            return false;
        }
    }
}
