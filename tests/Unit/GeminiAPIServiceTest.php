<?php

use App\Models\Reading;
use App\Services\GeminiAPIService;
use Database\Seeders\HexagramSeeder;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Resources\GenerativeModel;
use Gemini\Responses\GenerativeModel\GenerateContentResponse;

use function Pest\Laravel\seed;

describe('Gemini API Service', function () {
    beforeEach(function () use (&$service) {
        seed(HexagramSeeder::class);
        $service = resolve(GeminiAPIService::class);
    });

    it('generates interpretation in Russian', function () use (&$service) {
        app()->setLocale('ru');

        $reading = Reading::factory()->create([
            'question' => 'Стоит ли мне покупать этот дом?',
            'coin_results' => [6, 7, 7, 8, 8, 9], // Линии 1 и 6 меняются
            'binary' => '011001',
        ]);

        Gemini::fake([
            GenerateContentResponse::fake([
                'candidates' => [['content' => ['parts' => [['text' => 'Анализ: Дом, покупать...']]]]],
            ]),
        ]);

        /** @var GeminiAPIService $service */
        $service->getInterpretation($reading->load('hexagram.hexagramLines'));

        /** @phpstan-ignore-next-line */
        Gemini::assertSent(resource: GenerativeModel::class, model: 'gemini-3-flash-preview',
            callback: fn ($method, $params) => str_contains((string) $params[0], 'Линия 1') &&
                str_contains((string) $params[0], 'Линия 6') &&
                str_contains((string) $params[0], 'Основная гексаграмма')
        );
    });

    it('generates interpretation in English', function () use (&$service) {
        app()->setLocale('en');

        $reading = Reading::factory()->create([
            'question' => 'Should I buy this house?',
            'coin_results' => [6, 7, 7, 8, 8, 9],
            'binary' => '011001',
        ]);

        Gemini::fake([
            GenerateContentResponse::fake([
                'candidates' => [['content' => ['parts' => [['text' => 'Analysis: House, buy...']]]]],
            ]),
        ]);

        /** @var GeminiAPIService $service */
        $service->getInterpretation($reading->load('hexagram.hexagramLines'));

        /** @phpstan-ignore-next-line */
        Gemini::assertSent(resource: GenerativeModel::class, model: 'gemini-3-flash-preview',
            callback: fn ($method, $params) => str_contains((string) $params[0], 'Line 1')
                && str_contains((string) $params[0], 'Line 6')
                && str_contains((string) $params[0], 'Primary Hexagram')
        );
    });
});
