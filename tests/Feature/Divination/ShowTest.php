<?php

use App\Models\Reading;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('guest cannon visit any divination show page', function () {
    get(route('dashboard.divinations.show', 1))->assertRedirect('/login');
});

test('auth user can visit own divination show page', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $reading = Reading::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->get(route('dashboard.divinations.show', $reading->id))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Cabinet/Divinations/Show')
            ->has('reading')
            ->has('hexagram')
        );
});

test('auth user cannot visit other user divination show page', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $reading = Reading::factory()->create();

    actingAs($user)
        ->get(route('dashboard.divinations.show', $reading->id))
        ->assertForbidden();
});
