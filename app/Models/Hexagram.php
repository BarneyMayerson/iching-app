<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Hexagram extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $casts = [
        'names' => 'array',
        'lines' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Trigram, $this>
     */
    public function upperTrigram(): BelongsTo
    {
        return $this->belongsTo(Trigram::class, 'upper_trigram_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Trigram, $this>
     */
    public function lowerTrigram(): BelongsTo
    {
        return $this->belongsTo(Trigram::class, 'lower_trigram_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Line, $this>
     */
    public function hexagramLines(): HasMany
    {
        return $this->hasMany(Line::class);
    }

    /**
     * @return array<int, array{value: int, label: string}>
     */
    public static function getOptionsForSelect(): array
    {
        return Cache::rememberForever('hexagrams_select_options', fn () => self::orderBy('number')
            ->get(['number', 'character', 'names'])
            ->map(function (self $hex) {
                $number = Str::padLeft((string) $hex->number, 2, '0');

                return [
                    'value' => $hex->number,
                    'label' => "{$number}. {$hex->character} ({$hex->names[0]})",
                ];
            })
            ->toArray());
    }
}
