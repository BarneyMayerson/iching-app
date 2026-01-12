<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Trigram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrigramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('trigrams')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $jsonContent = file_get_contents(database_path('data/i-ching_data.json'));

        if (! is_string($jsonContent)) {
            throw new \RuntimeException('Failed to read JSON file');
        }

        $data = json_decode($jsonContent, true);

        foreach ($data['trigrams'] as $trigramData) {
            Trigram::create([
                'number' => $trigramData['number'],
                'name' => $trigramData['names'][0], // Первое имя как основное
                'chinese_name' => $trigramData['chineseName'],
                'pinyin_name' => $trigramData['pinyinName'],
                'character' => $trigramData['character'],
                'attribute' => $trigramData['attribute'],
                'image' => $trigramData['images'][0],
                'images' => json_encode($trigramData['images']),
                'chinese_image' => $trigramData['chineseImage'] ?? null,
                'pinyin_image' => $trigramData['pinyinImage'] ?? null,
                'family_relationship' => $trigramData['familyRelationship'] ?? null,
                'binary' => $trigramData['binary'],
                'lines' => json_encode($trigramData['lines']),
            ]);
        }

        $this->command->info(count($data['trigrams']).' trigrams have been loaded from JSON.');
    }
}
