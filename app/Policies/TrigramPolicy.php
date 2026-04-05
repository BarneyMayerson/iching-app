<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Trigram;
use App\Models\User;

class TrigramPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Trigram $trigram): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Trigram $trigram): bool
    {
        return true;
    }

    public function delete(User $user, Trigram $trigram): bool
    {
        return false;
    }

    public function restore(User $user, Trigram $trigram): bool
    {
        return false;
    }

    public function forceDelete(User $user, Trigram $trigram): bool
    {
        return false;
    }
}
