<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Reading;
use App\Models\User;
use App\Services\IChingService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<Reading>
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

        $readingData = resolve(IChingService::class)->makeReading(fake()->sentence, $coinResults);

        return [
            'user_id' => User::factory(),
            ...$readingData,
        ];
    }

    public function stable(): static
    {
        return $this->state(function (array $attributes) {
            $stableValues = [7, 7, 7, 8, 8, 8, 8, 8, 8, 7, 7, 7];
            $coinResults = Arr::random($stableValues, 6);

            return [
                'coin_results' => $coinResults,
                'binary' => resolve(IChingService::class)->coinResultsToBinary($coinResults),
                'secondary_binary' => null,
            ];
        });
    }
}
