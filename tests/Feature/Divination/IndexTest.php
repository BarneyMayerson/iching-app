<?php

use App\Models\Reading;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('guest cannon visit divinations index page', function () {
    get(route('cabinet.divinations.index'))->assertRedirect('/login');
});

test('user can see their divinations list', function () {
    /** @var User $user */
    $user = User::factory()->create();
    Reading::factory()->count(3)->create(['user_id' => $user->id]);

    actingAs($user)
        ->get(route('cabinet.divinations.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Cabinet/Divinations/Index')
            ->has('divinations', 3)
        );
});

test('user cannot see other users divinations list', function () {
    /** @var User $user */
    $user = User::factory()->create();
    Reading::factory()->count(2)->create();

    actingAs($user)
        ->get(route('cabinet.divinations.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Cabinet/Divinations/Index')
            ->has('divinations', 0)
        );
});
