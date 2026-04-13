<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Hexagram;
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
            'en' => GeminiPromptTemplates::SYSTEM_EN,
            'ru' => GeminiPromptTemplates::SYSTEM_RU,
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

        $linesContext = $this->buildChangingLinesContext($reading, $locale);

        $primaryJudgment = $primary->getTranslation('judgment', $locale);
        $secondaryPart = $this->buildSecondaryHexagramPart($secondary, $locale);

        $template = $locale === 'en'
            ? GeminiPromptTemplates::PROMPT_EN
            : GeminiPromptTemplates::PROMPT_RU;

        $noChangingLines = $locale === 'en'
            ? GeminiPromptTemplates::NO_CHANGING_LINES_EN
            : GeminiPromptTemplates::NO_CHANGING_LINES_RU;

        return sprintf(
            $template,
            $reading->question,
            $primary->number,
            $primary->names[0] ?? '',
            $primaryJudgment,
            $linesContext ?: $noChangingLines,
            $secondaryPart
        );
    }

    private function buildChangingLinesContext(Reading $reading, string $locale): string
    {
        $primary = $reading->hexagram;

        $changingPositions = collect($reading->coin_results)
            ->map(fn ($v, $i) => in_array($v, [6, 9]) ? $i + 1 : null)
            ->filter()
            ->values();

        if ($changingPositions->isEmpty()) {
            return '';
        }

        $prefix = $locale === 'en' ? 'Line' : 'Линия';

        return $primary->hexagramLines
            ->whereIn('position', $changingPositions)
            ->sortBy('position')
            ->map(fn ($l) => $prefix.' '.$l->position.': '.$l->getTranslation('meaning', $locale))
            ->join("\n");
    }

    private function buildSecondaryHexagramPart(?Hexagram $secondary, string $locale): string
    {
        if (! $secondary) {
            return $locale === 'en'
                ? GeminiPromptTemplates::SECONDARY_NONE_EN
                : GeminiPromptTemplates::SECONDARY_NONE_RU;
        }

        $judgment = $secondary->getTranslation('judgment', $locale);

        return $locale === 'en'
            ? "#{$secondary->number}. Judgment: {$judgment}"
            : "№{$secondary->number}. Суждение: {$judgment}";
    }
}
