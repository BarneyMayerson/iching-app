<?php

use App\Enums\Reading\InterpretationStatus;
use App\Jobs\InterpretReadingJob;
use App\Models\Reading;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Queue;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('requires authentication', function () {
    post(route('cabinet.divinations.store'))->assertRedirect(route('login'));
});

it('dispatches a job to interpret the reading', function () {
    Queue::fake();

    /** @var Reading $reading */
    $reading = Reading::factory()->create();

    actingAs($reading->user)
        ->post(route('cabinet.divinations.interpret', $reading))
        ->assertRedirect();

    Queue::assertPushed(InterpretReadingJob::class,
        fn ($job) => $job->reading->id === $reading->id
    );
});

it('cannot intrepert the same reading twice', function () {
    /** @var Reading $reading */
    $reading = Reading::factory()->create([
        'ai_responded_at' => now(),
        'ai_interpretation' => 'mock interpretation',
    ]);

    actingAs($reading->user)
        ->post(route('cabinet.divinations.interpret', $reading))
        ->assertStatus(Response::HTTP_FOUND)
        ->assertSessionHasErrors(['reading' => __('Interpretation already generated.')]);
});

it('requires author to interpret the reading', function () {
    /** @var Reading $reading */
    $reading = Reading::factory()->create();

    /** @var User $anotherUser */
    $anotherUser = User::factory()->create();

    actingAs($anotherUser)
        ->post(route('cabinet.divinations.interpret', $reading))
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('check interpretations limit', function () {
    /** @var User $user */
    $user = User::factory()->create();

    Reading::factory()->count(2)->for($user)->create([
        'ai_responded_at' => now(),
        'ai_interpretation' => 'mock interpretation',
    ]);

    $reading = Reading::factory()->for($user)->create();

    actingAs($user)
        ->post(route('cabinet.divinations.interpret', $reading))
        ->assertStatus(Response::HTTP_FOUND)
        ->assertSessionHasErrors(['limit' => __('Limit reached.')]);

    // when user delete even all readings
    $user->readings()->delete();
    $reading = Reading::factory()->for($user)->create();

    actingAs($user)
        ->post(route('cabinet.divinations.interpret', $reading))
        ->assertStatus(Response::HTTP_FOUND)
        ->assertSessionHasErrors(['limit' => __('Limit reached.')]);
});

it('throttles interpretation requests', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $reading = Reading::factory()->for($user)->create();

    // Первые 3 запроса должны пройти (или отклониться по бизнес-логике)
    for ($i = 0; $i < 3; $i++) {
        actingAs($user)
            ->post(route('cabinet.divinations.interpret', $reading));
    }

    // 4-й запрос должен быть заблокирован throttle
    actingAs($user)
        ->post(route('cabinet.divinations.interpret', $reading))
        ->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
});

it('allows user to cancel interpretation', function () {
    /** @var Reading $reading */
    $reading = Reading::factory()->create([
        'interpretation_status' => InterpretationStatus::PENDING,
    ]);

    actingAs($reading->user)
        ->patch(route('cabinet.divinations.cancel-interpretation', $reading))
        ->assertRedirect();

    $reading->refresh();

    expect($reading->interpretation_status)->toBe(InterpretationStatus::CANCELLED);
});
