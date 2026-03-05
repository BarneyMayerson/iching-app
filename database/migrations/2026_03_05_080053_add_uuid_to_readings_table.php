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
        Schema::table('readings', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable()->unique();
        });

        foreach (\App\Models\Reading::all() as $reading) {
            $reading->update(['uuid' => (string) \Illuminate\Support\Str::uuid()]);
        }

        Schema::table('readings', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('readings', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
