<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
