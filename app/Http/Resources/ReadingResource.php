<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Reading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Reading
 */
class ReadingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Carbon|null $aiRespondedAt */
        $aiRespondedAt = $this->ai_responded_at;

        return [
            'uuid' => $this->uuid,
            'question' => $this->question,
            'date' => $this->created_at->format('d.m.Y'),
            'time' => $this->created_at->format('H:i'),
            'relative_date' => $this->created_at->diffForHumans(),
            'binary' => $this->binary,
            'hexagram' => $this->whenLoaded('hexagram', fn () => HexagramResource::make($this->hexagram)),
            'secondary_binary' => $this->secondary_binary,
            'secondary_hexagram' => $this->whenLoaded('secondaryHexagram', fn () => HexagramResource::make($this->secondaryHexagram)),
            'coin_results' => $this->coin_results,
            'ai_interpretation' => $this->ai_interpretation,
            'ai_responded_at' => $aiRespondedAt?->format('d.m.Y H:i'),
            'interpretation_status' => $this->interpretation_status,
        ];
    }
}
