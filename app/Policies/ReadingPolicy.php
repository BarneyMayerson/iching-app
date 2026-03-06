<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Reading;
use App\Models\User;

class ReadingPolicy
{
    /**
     * Determine whether the user can export the model.
     */
    public function export(User $user, Reading $reading): bool
    {
        return $reading->user->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reading $reading): bool
    {
        return $reading->user->is($user);
    }
}
