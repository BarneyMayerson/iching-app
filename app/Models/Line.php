<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Line extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $casts = [
        'meaning' => 'json',
    ];

    /** @var list<string> */
    public array $translatable = ['meaning'];

    /**
     * @return BelongsTo<Hexagram, $this>
     */
    public function hexagram(): BelongsTo
    {
        return $this->belongsTo(Hexagram::class);
    }
}
