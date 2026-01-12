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
        Schema::create('trigrams', function (Blueprint $table): void {
            $table->id();
            $table->integer('number')->unique(); // 1-8
            $table->string('name'); // Основное английское имя
            $table->string('chinese_name');
            $table->string('pinyin_name');
            $table->string('character')->nullable(); // Символ ☰
            $table->string('attribute');
            $table->string('image'); // Основной образ
            $table->json('images')->nullable(); // Все образы
            $table->string('chinese_image')->nullable();
            $table->string('pinyin_image')->nullable();
            $table->string('family_relationship')->nullable();
            $table->string('binary', 3);
            $table->json('lines')->nullable(); // [1,1,1]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trigrams');
    }
};
