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
            $table->string('name'); // Основное английское имя
            $table->json('names')->nullable(); // Все имена
            $table->string('chinese_name');
            $table->string('pinyin_name');
            $table->string('character')->nullable(); // Символ Юникода
            $table->foreignId('upper_trigram_id')->constrained('trigrams');
            $table->foreignId('lower_trigram_id')->constrained('trigrams');
            $table->string('binary', 6);
            $table->json('lines')->nullable(); // [1,1,1,1,1,1]

            // Эти поля нужно будет заполнить позже из других источников
            $table->text('judgment')->nullable();
            $table->text('image')->nullable();
            $table->text('description')->nullable();
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
