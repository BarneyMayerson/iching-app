<?php

use App\Http\Resources\LineResource;
use App\Models\Line;
use Database\Seeders\HexagramSeeder;

use function Pest\Laravel\seed;

it('returns correctly formatted data for a line', function () {
    seed(HexagramSeeder::class);

    $line = Line::find(1);
    $resource = LineResource::make($line);

    expect($resource->toArray(request()))->toMatchArray([
        'position' => $line->position,
        'meaning' => $line->meaning,
    ]);
});
