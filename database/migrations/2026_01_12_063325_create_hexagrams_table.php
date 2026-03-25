<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hexagrams', function (Blueprint $table): void {
            $table->id();
            $table->integer('number')->unique(); // 1-64
            $table->json('names');
            $table->string('chinese_name');
            $table->string('pinyin_name');
            $table->string('character'); // Символ Юникода
            $table->foreignId('upper_trigram_id')->constrained('trigrams');
            $table->foreignId('lower_trigram_id')->constrained('trigrams');
            $table->string('binary', 6)->unique()->index();
            $table->json('lines'); // [1,1,1,1,1,1]
            $table->json('judgment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hexagrams');
    }
};
