<?php

use App\Enums\Reading\InterpretationStatus;
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
        Schema::create('readings', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->nullable()->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('question');
            $table->json('coin_results');
            $table->string('binary', 6);
            $table->string('secondary_binary', 6)->nullable();
            $table->text('ai_interpretation')->nullable();
            $table->timestamp('ai_responded_at')->nullable();
            $table->string('interpretation_status')->default(InterpretationStatus::NOT_STARTED->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('readings');
    }
};
