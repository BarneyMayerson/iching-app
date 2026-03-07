<?php

declare(strict_types=1);

namespace App\Services;

class IChingService
{
    /**
     * @param  list<int>  $coinResults  Массив из 6 значений 6-9
     * @return string Бинарная строка из 6 символов '0' или '1'
     */
    public function coinResultsToBinary(array $coinResults): string
    {
        $binary = '';

        foreach ($coinResults as $value) {
            // 7 или 9 = Ян = 1, 6 или 8 = Инь = 0
            $binary .= in_array($value, [7, 9], true) ? '1' : '0';
        }

        return $binary;
    }

    /**
     * @param  string  $question  Вопрос, который задает пользователь
     * @param  list<int>  $coinResults  Результаты бросков монет
     * @return array<string, mixed>
     */
    public function makeReading(string $question, array $coinResults): array
    {
        $binary = $this->coinResultsToBinary($coinResults);
        $chagingLines = $this->getChangingLines($coinResults);

        $secondaryBinary = empty($chagingLines)
            ? null
            : $this->applyChangingLines($binary, $chagingLines);

        return [
            'question' => $question,
            'coin_results' => $coinResults,
            'binary' => $binary,
            'secondary_binary' => $secondaryBinary,
        ];
    }

    /**
     * @param  list<int>  $coinResults  Массив из 6 значений 6-9
     * @return list<int> Позиции меняющихся линий (1-6, где 1 - нижняя линия, 6 - верхняя)
     */
    public function getChangingLines(array $coinResults): array
    {
        $changing = [];

        foreach ($coinResults as $index => $value) {
            if ($this->isLineChanging($value)) {
                $changing[] = $index;
            }
        }

        return $changing;
    }

    /**
     * @param  string  $binary  Исходная бинарная строка
     * @param  list<int>  $changingLines  Позиции меняющихся линий (0-5)
     * @return string Новая бинарная строка
     */
    public function applyChangingLines(string $binary, array $changingLines): string
    {
        $chars = str_split($binary);

        foreach ($changingLines as $position) {
            if (isset($chars[$position])) {
                // Меняем 0 на 1 или наоборот
                $chars[$position] = ($chars[$position] === '1') ? '0' : '1';
            }
        }

        return implode('', $chars);
    }

    public function isLineChanging(int $value): bool
    {
        return in_array($value, [6, 9], true);
    }

    public function getLineType(int $value): string
    {
        return match ($value) {
            6 => 'old_yin',
            7 => 'young_yang',
            8 => 'young_yin',
            9 => 'old_yang',
            default => throw new \InvalidArgumentException("Invalid line value: $value"),
        };
    }
}
