<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Reading;
use App\Models\User;

class ReadingPolicy
{
    public function view(User $user, Reading $reading): bool
    {
        return $reading->user->is($user) || $user->canAccessPanel(resolve('filament')->getPanel('adm'));
    }

    public function create(User $user): bool
    {
        return $user->canCreateReadingToday();
    }

    public function update(User $user, Reading $reading): bool
    {
        return $reading->user->is($user) || $user->canAccessPanel(resolve('filament')->getPanel('adm'));
    }

    public function interpret(User $user, Reading $reading): bool
    {
        return $reading->user->is($user) && $user->canInterpretReadingToday();
    }

    public function export(User $user, Reading $reading): bool
    {
        return $reading->user->is($user);
    }

    public function delete(User $user, Reading $reading): bool
    {
        return $reading->user->is($user);
    }
}
