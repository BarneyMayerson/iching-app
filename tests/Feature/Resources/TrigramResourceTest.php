<?php

use App\Http\Resources\TrigramResource;
use App\Models\Trigram;
use Database\Seeders\HexagramSeeder;

use function Pest\Laravel\seed;

it('returns correctly formatted data for a trigram', function () {
    seed(HexagramSeeder::class);

    $trigram = Trigram::find(1);
    $resource = TrigramResource::make($trigram);

    expect($resource->toArray(request()))->toMatchArray([
        'number' => $trigram->number,
        'symbol' => $trigram->character,
        'binary' => $trigram->binary,
        'attribute' => $trigram->attribute,
        'elements' => $trigram->images,
        'origins' => [
            'name' => [
                'chinese' => $trigram->chinese_name,
                'pinyin' => $trigram->pinyin_name,
            ],
            'image' => [
                'chinese' => $trigram->chinese_image,
                'pinyin' => $trigram->pinyin_image,
            ],
        ],
        'family' => $trigram->family_relationship,
    ]);
});
