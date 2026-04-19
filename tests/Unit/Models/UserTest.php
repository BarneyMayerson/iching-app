<?php

use App\Models\Reading;
use App\Models\User;

it('correctly calculates the daily redings limit', function () {
    $user = User::factory()->create();

    // Создаем 4 сегодняшних (одно из них удаляем)
    Reading::factory()->count(4)->for($user)->create();

    expect($user->canCreateReadingToday())->toBeTrue();

    Reading::factory()->for($user)->create();

    expect($user->canCreateReadingToday())->toBeFalse();
});

it('correctly calculates the daily interpretations limit', function () {
    $user = User::factory()->create();

    // Создаем 4 сегодняшних (одно из них удаляем)
    Reading::factory()->for($user)->create([
        'ai_responded_at' => now(),
    ]);

    expect($user->canInterpretReadingToday())->toBeTrue();

    Reading::factory()->for($user)->create([
        'ai_responded_at' => now(),
    ]);

    expect($user->canInterpretReadingToday())->toBeFalse();
});

it('counts deleted readings when check readings limits', function () {
    $user = User::factory()->create();

    Reading::factory()->count(5)->for($user)->create();

    expect($user->canCreateReadingToday())->toBeFalse();

    $user->readings()->delete();

    expect($user->canCreateReadingToday())->toBeFalse();
});

it('counts deleted readings when check interpretations limits', function () {
    $user = User::factory()->create();

    Reading::factory()->count(2)->for($user)->create([
        'ai_responded_at' => now(),
    ]);

    expect($user->canInterpretReadingToday())->toBeFalse();

    $user->readings()->delete();

    expect($user->canInterpretReadingToday())->toBeFalse();
});
