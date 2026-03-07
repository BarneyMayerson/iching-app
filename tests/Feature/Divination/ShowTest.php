<?php

use App\Http\Resources\ReadingResource;
use App\Models\Reading;
use App\Models\User;
use Database\Seeders\HexagramSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

test('guest cannon visit any divination show page', function () {
    get(route('cabinet.divinations.show', 1))->assertRedirect('/login');
});

test('auth user can visit own divination show page', function () {
    seed(HexagramSeeder::class);

    /** @var User $user */
    $user = User::factory()->create();

    /** @var Reading $reading */
    $reading = Reading::factory()->create(['user_id' => $user->id]);
    $reading->load(
        'hexagram',
        'hexagram.hexagramLines',
        'hexagram.upperTrigram',
        'hexagram.lowerTrigram',
        'secondaryHexagram',
        'secondaryHexagram.upperTrigram',
        'secondaryHexagram.lowerTrigram',
    );

    // @phpstan-ignore-next-line
    actingAs($user)
        ->get(route('cabinet.divinations.show', $reading))
        ->assertOk()
        ->assertComponentIs('Cabinet/Divinations/Show')
        ->assertHasResource('reading', ReadingResource::make($reading));
});

test('auth user cannot visit other user divination show page', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $reading = Reading::factory()->create();

    actingAs($user)
        ->get(route('cabinet.divinations.show', $reading))
        ->assertForbidden();
});
