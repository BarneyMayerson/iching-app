<?php

use App\Http\Resources\HexagramResource;
use App\Http\Resources\ReadingResource;
use App\Models\Reading;
use App\Services\IChingService;
use Database\Seeders\HexagramSeeder;

use function Pest\Laravel\seed;

it('returns correctly formatted data for a hexagram', function () {
    seed(HexagramSeeder::class);

    /** @var Reading $reading */
    $reading = Reading::factory()->create();

    $resource = ReadingResource::make($reading->load(['hexagram', 'secondaryHexagram']));

    $data = $resource->toArray(request());

    expect($data)->toHaveKeys([
        'uuid',
        'question',
        'date',
        'time',
        'relative_date',
        'binary',
        'hexagram',
        'secondary_binary',
        'secondary_hexagram',
        'coin_results',
    ]);

    /** @var list<int> $coinResults */
    $coinResults = $reading->coin_results;

    expect($data['binary'])->toBe(resolve(IChingService::class)->coinResultsToBinary($coinResults));

    expect($data['hexagram'])->toBeInstanceOf(HexagramResource::class);
});
