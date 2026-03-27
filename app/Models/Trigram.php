<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Trigram extends Model
{
    use HasTranslations;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $casts = [
        'names' => 'json',
        'attribute' => 'json',
        'images' => 'json',
        'family_relationship' => 'json',
        'lines' => 'array',
    ];

    /** @var list<string> */
    public array $translatable = ['names', 'attribute', 'images', 'family_relationship'];
}
