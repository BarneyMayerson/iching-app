<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Hexagram;
use App\Models\Line;
use App\Models\Trigram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HexagramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lines')->truncate();
        DB::table('hexagrams')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $jsonContent = file_get_contents(database_path('data/i-ching_data.json'));

        if (! is_string($jsonContent)) {
            throw new \RuntimeException('Failed to read JSON file');
        }

        $data = json_decode($jsonContent, true);

        // Убедимся, что триграммы загружены
        if (Trigram::count() !== 8) {
            $this->call(TrigramSeeder::class);
        }

        // Массив для быстрого поиска триграмм по номеру
        $trigramsByNumber = Trigram::all()->keyBy('number');

        $hexagramCount = 0;
        $lineCount = 0;

        foreach ($data['hexagrams'] as $hexagramData) {
            $hexagram = Hexagram::create([
                'number' => $hexagramData['number'],
                'name' => $hexagramData['names'][0], // Основное имя
                'names' => json_encode($hexagramData['names']),
                'chinese_name' => $hexagramData['chineseName'],
                'pinyin_name' => $hexagramData['pinyinName'],
                'character' => $hexagramData['character'],
                'upper_trigram_id' => $trigramsByNumber[$hexagramData['topTrigram']]->id,
                'lower_trigram_id' => $trigramsByNumber[$hexagramData['bottomTrigram']]->id,
                'binary' => $hexagramData['binary'],
                'lines' => json_encode($hexagramData['lines']),
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
