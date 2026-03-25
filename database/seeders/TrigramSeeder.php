<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Trigram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TrigramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('trigrams')->truncate();
        Schema::enableForeignKeyConstraints();

        $jsonContent = file_get_contents(database_path('data/i-ching_data_multilang.json'));

        if (! is_string($jsonContent)) {
            throw new \RuntimeException('Failed to read JSON file');
        }

        $data = json_decode($jsonContent, true);

        foreach ($data['trigrams'] as $trigramData) {
            Trigram::create([
                'number' => $trigramData['number'],
                'names' => $trigramData['names'],
                'chinese_name' => $trigramData['chineseName'],
                'pinyin_name' => $trigramData['pinyinName'],
                'character' => $trigramData['character'],
                'attribute' => $trigramData['attribute'],
                'images' => $trigramData['images'],
                'chinese_image' => $trigramData['chineseImage'],
                'pinyin_image' => $trigramData['pinyinImage'],
                'family_relationship' => $trigramData['familyRelationship'],
                'binary' => $trigramData['binary'],
                'lines' => $trigramData['lines'],
            ]);
        }

        $this->command->info(count($data['trigrams']).' trigrams have been loaded from JSON.');
    }
}
