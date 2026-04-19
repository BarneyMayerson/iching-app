<?php

use App\Models\Reading;
use App\Models\User;
use App\Services\GeminiAPIService;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('requires authentication', function () {
    post(route('cabinet.divinations.store'))->assertRedirect(route('login'));
});

it('can interpret user reading', function () {
    /** @var Reading $reading */
    $reading = Reading::factory()->create();

    $reading->load('hexagram.hexagramLines', 'secondaryHexagram');
    $mockInterpretation = 'mock interpretation';

    $mockAiService = mock(GeminiAPIService::class);

    $mockAiService->shouldReceive('getInterpretation')
        ->once() // @phpstan-ignore-line
        ->with(Mockery::on(
            // Сравниваем ID моделей
            fn ($argument) => $argument->id === $reading->id))
        ->andReturn($mockInterpretation);

    app()->instance(GeminiAPIService::class, $mockAiService);

    $response = actingAs($reading->user)
        ->post(route('cabinet.divinations.interpret', $reading));

    $response->assertRedirect();
    $response->assertSessionHas('message', __('Interpretation generated successfully.'));

    $reading->refresh();
    expect($reading->ai_interpretation)->toBe($mockInterpretation);
    expect($reading->ai_responded_at)->not->toBeNull();
});

it('cannot intrepert the same reading twice', function () {
    /** @var Reading $reading */
    $reading = Reading::factory()->create([
        'ai_responded_at' => now(),
        'ai_interpretation' => 'mock interpretation',
    ]);

    actingAs($reading->user)
        ->post(route('cabinet.divinations.interpret', $reading))
        ->assertRedirectBack()
        ->assertSessionHas('error', __('Interpretation already generated.'));
});

it('requires author to interpret the reading', function () {
    /** @var Reading $reading */
    $reading = Reading::factory()->create();

    /** @var User $anotherUser */
    $anotherUser = User::factory()->create();

    actingAs($anotherUser)
        ->post(route('cabinet.divinations.interpret', $reading))
        ->assertRedirect(route('cabinet.divinations.index'))
        ->assertSessionHas('error', __('You cannot update this reading.'));
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
        ->assertRedirect(route('cabinet.divinations.index'))
        ->assertSessionHas('error', __('Limit reached.'));

    // when user delete even all readings
    $user->readings()->delete();
    $reading = Reading::factory()->for($user)->create();

    actingAs($user)
        ->post(route('cabinet.divinations.interpret', $reading))
        ->assertRedirect(route('cabinet.divinations.index'))
        ->assertSessionHas('error', __('Limit reached.'));
});
