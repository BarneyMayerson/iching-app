<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class StaticPage extends Model
{
    use HasTranslations;

    protected $casts = [
        'title' => 'json',
        'content' => 'json',
    ];

    /** @var list<string> */
    public array $translatable = ['title', 'content'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
