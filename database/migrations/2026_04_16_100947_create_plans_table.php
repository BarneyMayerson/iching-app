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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 'Free', 'Standard', 'Premium'
            $table->string('slug')->unique();
            $table->unsignedInteger('daily_readings_limit')->default(5);
            $table->unsignedInteger('daily_interpretations_limit')->default(2);
            $table->unsignedInteger('price_cents')->default(0);
            $table->boolean('is_custom_price')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('plan_id')->after('email')->default(1)->constrained('plans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('plan_id');
        });

        Schema::dropIfExists('plans');
    }
};
