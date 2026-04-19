<?php

use App\Http\Resources\ReadingResource;
use App\Models\Reading;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('guest cannon visit divinations index page', function () {
    get(route('cabinet.divinations.index'))->assertRedirect('/login');
});

test('user can see their divinations list', function () {
    /** @var User $user */
    $user = User::factory()->create();
    Reading::factory()->count(2)->create(['user_id' => $user->id]);

    $expectedData = $user->readings()
        ->with('hexagram')
        ->latest()
        ->paginate(12);

    $expectedResource = ReadingResource::collection($expectedData);

    actingAs($user)
        ->get(route('cabinet.divinations.index'))
        ->assertOk()
        ->assertComponentIs('Cabinet/Divinations/Index')
        ->assertHasPaginatedResource('readings', $expectedResource);

    expect($expectedResource->count())->toBe(2);
});

test('user cannot see other users divinations list', function () {
    /** @var User $user */
    $user = User::factory()->create();
    Reading::factory()->count(2)->create();

    $expectedResource = ReadingResource::collection($user->readings()->latest()->paginate(12)->withQUeryString());

    actingAs($user)
        ->get(route('cabinet.divinations.index'))
        ->assertOk()
        ->assertComponentIs('Cabinet/Divinations/Index')
        ->assertHasPaginatedResource('readings', $expectedResource);

    expect($expectedResource->count())->toBe(0);
});
