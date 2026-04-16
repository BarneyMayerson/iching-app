<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Free',
            'slug' => 'free',
            'daily_readings_limit' => 5,
            'daily_interpretations_limit' => 2,
            'price_cents' => 0,
        ];
    }

    public function standard(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Standard',
            'slug' => 'standard',
            'daily_readings_limit' => 12,
            'daily_interpretations_limit' => 8,
            'price_cents' => 500,
        ]);
    }
}
