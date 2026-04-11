<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Reading;
use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;

class GeminiAPIService
{
    public function getInterpretation(Reading $reading): string
    {
        $locale = app()->getLocale();
        $prompt = $this->buildPrompt($reading, $locale);

        $systemInstructions = [
            'ru' => 'Ты — аналитический модуль интерпретации И-Цзин. Выполни 4 шага анализа и заверши ответ. Запрещено предлагать помощь или задавать вопросы.',
            'en' => 'You are an I Ching analytical module. Complete the 4-step analysis and finish your response. Do not offer further help or ask follow-up questions.',
        ];

        return Gemini::generativeModel('gemini-3-flash-preview')
            ->withSystemInstruction(
                Content::parse($systemInstructions[$locale] ?? $systemInstructions['en'])
            )
            ->generateContent($prompt)
            ->text();
    }

    private function buildPrompt(Reading $reading, string $locale): string
    {
        $primary = $reading->hexagram;
        $secondary = $reading->secondaryHexagram;

        $linesContext = $primary->hexagramLines
            ->whereIn('position', collect($reading->coin_results)->map(fn ($v, $i) => in_array($v, [6, 9]) ? $i + 1 : null)->filter())
            ->values()
            ->map(function ($l) use ($locale) {
                $prefix = $locale === 'ru' ? 'Линия' : 'Line';
                $meaning = $l->getTranslation('meaning', $locale);

                return "{$prefix} {$l->position}: {$meaning}";
            })
            ->join("\n");

        $primaryJudgment = $primary->getTranslation('judgment', $locale);
        $secondaryJudgment = $secondary ? $secondary->getTranslation('judgment', $locale) : '';

        if ($locale === 'ru') {
            return "Проанализируй запрос к И-Цзин:
1. Из вопроса: \"{$reading->question}\" выдели СУЩЕСТВИТЕЛЬНЫЕ, ГЛАГОЛЫ и ВРЕМЯ.
2. Основная гексаграмма №{$primary->number}: \"{$primary->names[0]}\". Суждение: {$primaryJudgment}.
   Изменяющиеся линии:
   ".($linesContext ?: 'нет').'
3. Вторичная гексаграмма: '.($secondary ? "№{$secondary->number}. Суждение: {$secondaryJudgment}" : 'отсутствует (статична)').'.
4. ИТОГОВЫЙ СОВЕТ: Свяжи выделенные слова с символизмом гексаграмм и дай четкий ответ.';
        }

        return "Analyze the I Ching inquiry:
1. Extract NOUNS, VERBS, and TIME constraints from the question: \"{$reading->question}\".
2. Primary Hexagram #{$primary->number}: \"{$primary->names[0]}\". Judgment: {$primaryJudgment}.
   Changing Lines:
   ".($linesContext ?: 'none').'
3. Secondary Hexagram: '.($secondary ? "#{$secondary->number}. Judgment: {$secondaryJudgment}" : 'none (static)').'.
4. FINAL ADVICE: Connect the extracted keywords with the hexagram symbolism and provide a clear answer.';
    }
}
