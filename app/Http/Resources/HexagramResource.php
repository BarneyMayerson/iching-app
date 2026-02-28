<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Hexagram
 */
class HexagramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'binary' => $this->binary,
            'number' => $this->number,
            'character' => $this->character,
            'names' => $this->names,
            'origins' => [
                'chinese' => $this->chinese_name,
                'pinyin' => $this->pinyin_name,
            ],
            'judgment' => $this->judgment,
            'image' => $this->image,
            'lines' => LineResource::collection($this->whenLoaded('hexagramLines')),
        ];
    }
}
