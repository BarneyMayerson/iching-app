<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('requires authentication', function (): void {
    post(route('dashboard.divinations.store'))->assertRedirect(route('login'));
});

it('stores an authenticated user cast an i-ching reading', function (): void {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);
    $question = 'Should I start something new?';

    post(route('dashboard.divinations.store'), [
        'question' => $question,
    ]);

    assertDatabaseHas('readings', [
        'user_id' => $user->id,
        'question' => $question,
    ]);
});
