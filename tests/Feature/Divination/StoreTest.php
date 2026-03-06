<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('requires authentication', function () {
    post(route('cabinet.divinations.store'))->assertRedirect(route('login'));
});

it('stores an auth user cast an i-ching reading and redirects to the divination show page', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $question = 'Should I start something new?';

    $response = actingAs($user)
        ->post(route('cabinet.divinations.store'), [
            'question' => $question,
            'coin_results' => [6, 7, 7, 9, 6, 7],
        ]);

    $reading = \App\Models\Reading::first();

    $response->assertRedirect(route('cabinet.divinations.show', $reading));

    assertDatabaseHas('readings', [
        'user_id' => $user->id,
        'question' => $question,
        'coin_results' => json_encode([6, 7, 7, 9, 6, 7]),
    ]);
});

it('fails validation if question is missing', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('cabinet.divinations.store'), ['question' => ''])
        ->assertSessionHasErrors('question');
});

it('fails validation if question exceeds 255 characters', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $longQuestion = str_repeat('a', 256);

    actingAs($user)
        ->post(route('cabinet.divinations.store'), [
            'question' => $longQuestion,
        ])
        ->assertSessionHasErrors('question');
});

it('fails validation if coin_results is missing', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('cabinet.divinations.store'), [
            'question' => 'Valid question',
        ])
        ->assertSessionHasErrors('coin_results');
});

it('fails validation if coin_results is not an array of 6 integers between 6 and 9', function () {
    /** @var User $user */
    $user = User::factory()->create();

    // Invalid because it's not an array
    actingAs($user)
        ->post(route('cabinet.divinations.store'), [
            'question' => 'Valid question',
            'coin_results' => 'not an array',
        ])
        ->assertSessionHasErrors('coin_results');

    // Invalid because it doesn't have exactly 6 items
    actingAs($user)
        ->post(route('cabinet.divinations.store'), [
            'question' => 'Valid question',
            'coin_results' => [6, 7, 8],
        ])
        ->assertSessionHasErrors('coin_results');

    // Invalid because it contains values outside the allowed range
    actingAs($user)
        ->post(route('cabinet.divinations.store'), [
            'question' => 'Valid question',
            'coin_results' => [5, 10, 6, 7, 8, 9],
        ])
        ->assertSessionHasErrors('coin_results.0')
        ->assertSessionHasErrors('coin_results.1');
});
