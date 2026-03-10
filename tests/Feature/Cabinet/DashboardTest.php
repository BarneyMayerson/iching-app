<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('guest cannon visit cabinet dashboard page', function () {
    get(route('cabinet.dashboard'))->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function (): void {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    $response = get(route('cabinet.dashboard'));
    $response->assertOk();
});
