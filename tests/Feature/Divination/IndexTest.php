<?php

use App\Http\Resources\ReadingResource;
use App\Models\Reading;
use App\Models\User;
use Database\Seeders\HexagramSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

test('guest cannon visit divinations index page', function () {
    get(route('cabinet.divinations.index'))->assertRedirect('/login');
});

test('user can see their divinations list', function () {
    seed(HexagramSeeder::class);

    /** @var User $user */
    $user = User::factory()->create();
    Reading::factory()->count(3)->create(['user_id' => $user->id]);

    // @phpstan-ignore-next-line
    actingAs($user)
        ->get(route('cabinet.divinations.index'))
        ->assertOk()
        ->assertComponentIs('Cabinet/Divinations/Index')
        ->assertHasResource('divinations', ReadingResource::collection($user->readings()->latest()->get()));
});

test('user cannot see other users divinations list', function () {
    /** @var User $user */
    $user = User::factory()->create();
    Reading::factory()->count(2)->create();

    $divinations = ReadingResource::collection($user->readings()->latest()->get());

    // @phpstan-ignore-next-line
    actingAs($user)
        ->get(route('cabinet.divinations.index'))
        ->assertOk()
        ->assertComponentIs('Cabinet/Divinations/Index')
        ->assertHasResource('divinations', $divinations);

    expect($divinations->count())->toBe(0);
});
