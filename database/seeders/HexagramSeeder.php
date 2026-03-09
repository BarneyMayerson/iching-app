<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Hexagram;
use App\Models\Line;
use App\Models\Trigram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HexagramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('lines')->truncate();
        DB::table('hexagrams')->truncate();
        Schema::enableForeignKeyConstraints();

        $jsonContent = file_get_contents(database_path('data/i-ching_data.json'));

        if (! is_string($jsonContent)) {
            throw new \RuntimeException('Failed to read JSON file');
        }

        $data = json_decode($jsonContent, true);

        // Убедимся, что триграммы загружены
        if (Trigram::count() !== 8) {
            $this->call(TrigramSeeder::class);
        }

        $hexagramCount = 0;
        $lineCount = 0;

        foreach ($data['hexagrams'] as $hexagramData) {
            $hexagram = Hexagram::create([
                'number' => $hexagramData['number'],
                'name' => $hexagramData['names'][0], // Основное имя
                'names' => $hexagramData['names'],
                'chinese_name' => $hexagramData['chineseName'],
                'pinyin_name' => $hexagramData['pinyinName'],
                'character' => $hexagramData['character'],
                'upper_trigram_id' => $hexagramData['topTrigram'],
                'lower_trigram_id' => $hexagramData['bottomTrigram'],
                'binary' => $hexagramData['binary'],
                'lines' => $hexagramData['lines'],
                'judgment' => $hexagramData['description'] ?? null,
                'image' => null,
                'description' => null,
            ]);

            $hexagramCount++;

            $lineData = array_filter($data['lines'], fn ($line) => $line['hexagram_id'] === $hexagramData['number']);

            foreach ($lineData as $lineItem) {
                Line::create([
                    'hexagram_id' => $hexagram->id,
                    'position' => $lineItem['position'],
                    'meaning' => $lineItem['meaning'],
                    'changing_meaning' => null,
                ]);
                $lineCount++;
            }
        }

        $this->command->info($hexagramCount.' hexagrams and '.$lineCount.' lines have been loaded from JSON.');
    }
}
