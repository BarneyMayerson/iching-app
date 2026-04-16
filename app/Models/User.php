<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email === 'owner@iching.lan';
    }

    /**
     * @return BelongsTo<Plan, $this>
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * @return HasMany<Reading, $this>
     */
    public function readings(): HasMany
    {
        return $this->hasMany(Reading::class);
    }

    public function getDailyReadingsLimit(): int
    {
        return $this->plan?->daily_readings_limit ?? 1;
    }

    public function getDailyInterpretationsLimit(): int
    {
        return $this->plan?->daily_interpretations_limit ?? 1;
    }

    public function canCreateReadingToday(): bool
    {
        return $this->canPerformToday(
            'readings',
            'created_at',
            fn () => $this->getDailyReadingsLimit()
        );
    }

    public function canInterpretReadingToday(): bool
    {
        return $this->canPerformToday(
            'readings',
            'ai_responded_at',
            fn () => $this->getDailyInterpretationsLimit()
        );
    }

    private function canPerformToday(string $relation, string $dateColumn, callable $getLimit): bool
    {
        $todayCount = $this->$relation()
            ->withTrashed()
            ->whereDate($dateColumn, now())
            ->count();

        return $todayCount < $getLimit();
    }
}
