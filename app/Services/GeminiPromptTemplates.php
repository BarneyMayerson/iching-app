<?php

declare(strict_types=1);

namespace App\Services;

final class GeminiPromptTemplates
{
    public const SYSTEM_EN = 'You are an I Ching analytical module. Complete the 4-step analysis and finish your response. Do not offer further help or ask follow-up questions.';

    public const SYSTEM_RU = 'Ты — аналитический модуль интерпретации И-Цзин. Выполни 4 шага анализа и заверши ответ. Запрещено предлагать помощь или задавать вопросы.';

    public const PROMPT_EN = <<<'EOT'
Analyze the I Ching inquiry:
1. Extract NOUNS, VERBS, and TIME constraints from the question: "%s".
2. Primary Hexagram #%d: "%s". Judgment: %s.
   Changing Lines: %s
3. Secondary Hexagram: %s.
4. FINAL ADVICE: Connect the extracted keywords with the hexagram symbolism and provide a clear answer.
EOT;

    public const PROMPT_RU = <<<'EOT'
Проанализируй запрос к И-Цзин:
1. Из вопроса: "%s" выдели СУЩЕСТВИТЕЛЬНЫЕ, ГЛАГОЛЫ и ВРЕМЯ.
2. Основная гексаграмма №%d: "%s". Суждение: %s.
   Изменяющиеся линии: %s
3. Вторичная гексаграмма: %s.
4. ИТОГОВЫЙ СОВЕТ: Свяжи выделенные слова с символизмом гексаграмм и дай четкий ответ.
EOT;

    public const NO_CHANGING_LINES_RU = 'нет';

    public const NO_CHANGING_LINES_EN = 'none';

    public const SECONDARY_NONE_EN = 'none (static)';

    public const SECONDARY_NONE_RU = 'отсутствует (статична)';
}
