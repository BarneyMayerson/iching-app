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
        ]);

    $reading = \App\Models\Reading::first();

    $response->assertRedirect(route('cabinet.divinations.show', $reading->id));

    assertDatabaseHas('readings', [
        'user_id' => $user->id,
        'question' => $question,
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

it('stores the actual question from the request', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $myQuestion = 'Will it rain tomorrow?';

    actingAs($user)
        ->post(route('cabinet.divinations.store'), ['question' => $myQuestion]);

    assertDatabaseHas('readings', [
        'question' => $myQuestion,
        'user_id' => $user->id,
    ]);
});
