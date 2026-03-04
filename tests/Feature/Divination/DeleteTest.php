<?php

use App\Models\Reading;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;

it('requires authentication', function () {
    $reading = Reading::factory()->create();

    delete(route('cabinet.divinations.delete', $reading))->assertRedirect(route('login'));
});

it('allows a user to delete their own reading', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $reading = Reading::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->delete(route('cabinet.divinations.delete', $reading))
        ->assertRedirect(route('cabinet.divinations.index'));

    assertDatabaseMissing('readings', ['id' => $reading->id]);
});

it('forbids a user from deleting someone else\'s reading', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $reading = Reading::factory()->create(['user_id' => $otherUser->id]);

    actingAs($user)
        ->delete(route('cabinet.divinations.delete', $reading))
        ->assertForbidden();

    assertDatabaseHas('readings', ['id' => $reading->id]);
});

it('returns 404 when trying to delete a non-existent reading', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->delete(route('cabinet.divinations.delete', 9999))
        ->assertNotFound();
});
