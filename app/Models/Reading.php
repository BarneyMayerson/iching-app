<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Reading\InterpretationStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reading extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected function casts(): array
    {
        return [
            'coin_results' => 'array',
            'ai_responded_at' => 'datetime',
            'interpretation_status' => InterpretationStatus::class,
        ];
    }

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
