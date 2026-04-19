<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Free',
            'slug' => 'free',
            'daily_readings_limit' => 5,
            'daily_interpretations_limit' => 2,
            'price_cents' => 0,
        ]);

        Plan::create([
            'name' => 'Standard',
            'slug' => 'standard',
            'daily_readings_limit' => 12,
            'daily_interpretations_limit' => 8,
            'price_cents' => 500,
        ]);

        $this->command->info('Plans have been loaded.');
    }
}
