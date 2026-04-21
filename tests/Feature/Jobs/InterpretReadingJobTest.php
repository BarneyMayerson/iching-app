<?php

use App\Jobs\InterpretReadingJob;
use App\Models\Reading;
use App\Services\GeminiAPIService;

it('updates reading with interpretation from AI service', function () {
    $reading = Reading::factory()->create();
    $mockInterpretation = 'The divine message';

    $mockAiService = mock(GeminiAPIService::class);
    $mockAiService->shouldReceive('getInterpretation')->andReturn($mockInterpretation); // @phpstan-ignore-line

    $job = new InterpretReadingJob($reading);
    app()->call($job->handle(...), ['aiService' => $mockAiService]);

    // @phpstan-ignore-next-line
    expect($reading->refresh())
        ->ai_interpretation->toBe($mockInterpretation)
        ->ai_responded_at->not->toBeNull();
});
