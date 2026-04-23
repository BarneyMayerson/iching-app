<?php

use Database\Seeders\PlanSeeder;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\seed;

test('registration screen can be rendered', function () {
    $response = get(route('register'));

    $response->assertStatus(200);
});

test('new users can register', function (): void {
    seed(PlanSeeder::class);

    $response = post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    assertAuthenticated();
    $response->assertRedirect(route('cabinet.dashboard', absolute: false));
});
