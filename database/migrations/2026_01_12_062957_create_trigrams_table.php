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
            $table->json('names');
            $table->string('chinese_name');
            $table->string('pinyin_name');
            $table->string('character'); // Символ ☰
            $table->json('attribute');
            $table->json('images');
            $table->string('chinese_image');
            $table->string('pinyin_image');
            $table->json('family_relationship');
            $table->string('binary', 3);
            $table->json('lines'); // [1,1,1]
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
