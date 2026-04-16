<?php

use App\Models\Reading;
use App\Models\User;

it('correctly calculates the daily limit', function () {
    $user = User::factory()->create();

    // Создаем 4 старых гадания
    Reading::factory()->count(4)->for($user)->create(['created_at' => now()->subDay()]);
    expect($user->canCreateReadingToday())->toBeTrue();

    // Создаем 4 сегодняшних (одно из них удаляем)
    Reading::factory()->count(3)->for($user)->create();
    Reading::factory()->for($user)->create()->delete();

    expect($user->canCreateReadingToday())->toBeFalse();
});
