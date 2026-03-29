<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reading extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'coin_results' => 'array',
    ];

    /**
     * @return list<string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Hexagram, $this>
     */
    public function hexagram(): BelongsTo
    {
        return $this->belongsTo(Hexagram::class, 'binary', 'binary');
    }

    /**
     * @return BelongsTo<Hexagram, $this>
     */
    public function secondaryHexagram(): BelongsTo
    {
        return $this->belongsTo(Hexagram::class, 'secondary_binary', 'binary');
    }
}
