<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Reading\InterpretationStatus;
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

    public function cancelInterpretation(User $user, Reading $reading): bool
    {
        return $reading->user->is($user)
            && in_array($reading->interpretation_status, [
                InterpretationStatus::PENDING,
                InterpretationStatus::PROCESSING,
                InterpretationStatus::CANCELLED,
            ]);
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
