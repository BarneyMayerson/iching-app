<?php

use App\Http\Resources\HexagramResource;
use App\Http\Resources\LineResource;
use App\Http\Resources\TrigramResource;
use App\Models\Hexagram;
use Database\Seeders\HexagramSeeder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use function Pest\Laravel\seed;

it('returns correctly formatted data for a hexagram', function () {
    seed(HexagramSeeder::class);

    /** @var Hexagram $hexagram */
    $hexagram = Hexagram::with('hexagramLines')->find(1);

    $resource = HexagramResource::make($hexagram);

    $data = $resource->toArray(request());

    expect($data)->toHaveKeys([
        'binary',
        'number',
        'character',
        'names',
        'origins',
        'judgment',
        'lines',
        'top_trigram',
        'bottom_trigram',
    ]);

    expect($data['binary'])->toBe($hexagram->binary);
    expect($data['number'])->toBe($hexagram->number);
    expect($data['origins']['chinese'])->toBe($hexagram->chinese_name);

    expect($data['lines'])->toBeInstanceOf(AnonymousResourceCollection::class);
    expect($data['lines'])->toHaveCount(6);
    $firstLine = $data['lines']->first();
    expect($firstLine)->toBeInstanceOf(LineResource::class);

    expect($data['top_trigram'])->toBeInstanceOf(TrigramResource::class);
    expect($data['bottom_trigram'])->toBeInstanceOf(TrigramResource::class);
});
