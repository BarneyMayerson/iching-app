<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Services\IChingService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reading>
 */
class ReadingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $values = [6, 7, 7, 7, 7, 7, 8, 8, 8, 8, 8, 8, 8, 8, 8, 9, 9, 9];
        $coinResults = Arr::random($values, 6);

        return [
            'question' => fake()->sentence,
            'user_id' => User::factory(),
            'coin_results' => $coinResults,
            'binary' => app(IChingService::class)->coinResultsToBinary($coinResults),

        ];
    }

    public function stable(): static
    {
        return $this->state(function (array $attributes) {
            $stableValues = [7, 7, 7, 8, 8, 8, 8, 8, 8, 7, 7, 7];
            $coinResults = Arr::random($stableValues, 6);

            return [
                'coin_results' => $coinResults,
                'binary' => app(IChingService::class)->coinResultsToBinary($coinResults),
            ];
        });
    }
}
