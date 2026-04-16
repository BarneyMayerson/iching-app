<?php

use App\Models\Reading;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('guest cannon visit divinations create page', function () {
    get(route('cabinet.divinations.create'))->assertRedirect('/login');
});

test('auth user can visit divination create page', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('cabinet.divinations.create'))
        ->assertOk()
        ->assertComponentIs('Cabinet/Divinations/Create');
});

test('auth user cannot visit divination create page due to the daily limit', function () {
    /** @var User $user */
    $user = User::factory()->create();

    Reading::factory()->count(4)->for($user)->create();

    actingAs($user)
        ->get(route('cabinet.divinations.create'))
        ->assertRedirect(route('cabinet.divinations.index'));
});
