<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Hexagram;
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
                'judgment' => null,
                'image' => null,
                'description' => null,
            ]);
        }

        $this->command->info(count($data['hexagrams']).' hexagrams have been loaded  from JSON.');
        $this->command->info('Note: Interpretations (judgment, image) must be filled in separately from other sources.');
    }
}
