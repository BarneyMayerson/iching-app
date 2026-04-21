<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\Reading\InterpretationStatus;
use App\Models\Reading;
use App\Services\GeminiAPIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class InterpretReadingJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(public Reading $reading) {}

    /**
     * Execute the job.
     */
    public function handle(GeminiAPIService $aiService): void
    {
        if ($this->reading->ai_responded_at) {
            return;
        }

        try {
            $this->reading->update(['interpretation_status' => InterpretationStatus::PROCESSING]);

            $interpretation = $aiService->getInterpretation($this->reading);

            $this->reading->update([
                'ai_interpretation' => $interpretation,
                'ai_responded_at' => now(),
                'interpretation_status' => InterpretationStatus::COMPLETED,
            ]);
        } catch (\Throwable $e) {
            $this->failed($e);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        $this->reading->update(['interpretation_status' => InterpretationStatus::FAILED]);
    }
}
