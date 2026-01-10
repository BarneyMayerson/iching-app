<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('guests are redirected to the login page', function (): void {
    $response = get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function (): void {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    $response = get(route('dashboard'));
    $response->assertOk();
});
