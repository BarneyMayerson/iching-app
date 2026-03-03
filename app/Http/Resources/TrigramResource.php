<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Trigram
 */
class TrigramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'number' => $this->number,
            'name' => $this->name,
            'symbol' => $this->character,
            'binary' => $this->binary,
            'attribute' => $this->attribute,
            'elements' => $this->images, // массив ["wind", "wood"]
            'origins' => [
                'name' => [
                    'chinese' => $this->chinese_name,
                    'pinyin' => $this->pinyin_name,
                ],
                'image' => [
                    'chinese' => $this->chinese_image,
                    'pinyin' => $this->pinyin_image,
                ],
            ],
            'family' => $this->family_relationship,
        ];
    }
}
