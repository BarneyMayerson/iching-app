<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Trigram;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @mixin \App\Models\Hexagram
 */
class HexagramResource extends JsonResource
{
    /** @var Collection<string, Trigram>|null */
    protected static $trigramsCache = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (is_null(self::$trigramsCache)) {
            self::$trigramsCache = Trigram::all()->keyBy('binary');
        }

        $reverseBinary = strrev($this->binary);
        $bottomTrigramBinary = substr($reverseBinary, 3, 3);
        $topTrigramBinary = substr($reverseBinary, 0, 3);

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
            'top_trigram' => TrigramResource::make(self::$trigramsCache->get($topTrigramBinary)),
            'bottom_trigram' => TrigramResource::make(self::$trigramsCache->get($bottomTrigramBinary)),
        ];
    }
}
