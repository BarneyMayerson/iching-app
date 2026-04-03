<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Hexagram;
use App\Models\User;

class HexagramPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Hexagram $hexagram): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Hexagram $hexagram): bool
    {
        return true;
    }

    public function delete(User $user, Hexagram $hexagram): bool
    {
        return false;
    }

    public function restore(User $user, Hexagram $hexagram): bool
    {
        return false;
    }

    public function forceDelete(User $user, Hexagram $hexagram): bool
    {
        return false;
    }
}
