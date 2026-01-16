<?php

declare(strict_types=1);

namespace App\Services;

class IChingService
{
    private const HEADS = 3; // Аверс (Ян)

    private const TAILS = 2; // Реверс (Инь)

    /**
     * @return int Возвращает 6, 7, 8 или 9
     */
    public function castSingleLine(): int
    {
        // === ПЕРВЫЙ ЭТАП: Определяем Инь или Ян ===
        $firstCoin = $this->tossCoin();

        if ($firstCoin === self::TAILS) { // Инь (2)
            // === ВТОРОЙ ЭТАП для Инь ===
            // Бросаем три монеты
            $threeCoins = [$this->tossCoin(), $this->tossCoin(), $this->tossCoin()];
            $sum = array_sum($threeCoins);

            // Старый Инь (6) только при трех решках (2+2+2=6)
            // Молодой Инь (8) при любой другой сумме
            return ($sum === 6) ? 6 : 8;

        } else { // Ян (3)
            // === ВТОРОЙ ЭТАП для Ян ===
            // Бросаем три монеты
            $threeCoins = [$this->tossCoin(), $this->tossCoin(), $this->tossCoin()];
            $sum = array_sum($threeCoins);

            // Старый Ян (9) только при двух решках и одном орле (2+2+3=7)
            // Молодой Ян (7) при любой другой сумме
            return ($sum === 7) ? 9 : 7;
        }
    }

    /**
     * @return list<int> Массив из 6 значений: 6, 7, 8 или 9
     */
    public function castCoins(): array
    {
        $lines = [];

        for ($i = 0; $i < 6; $i++) {
            $lines[] = $this->castSingleLine();
        }

        return $lines;
    }

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
     * @return array<string, mixed>
     */
    public function createReading(string $question): array
    {
        $coinResults = $this->castCoins();
        $binary = $this->coinResultsToBinary($coinResults);

        return [
            'question' => $question,
            'coin_results' => $coinResults,
            'binary' => $binary,
        ];
    }

    /**
     * @param  list<int>  $coinResults  Массив из 6 значений 6-9
     * @return list<int> Позиции меняющихся линий (1-6)
     */
    public function getChangingLines(array $coinResults): array
    {
        $changing = [];

        foreach ($coinResults as $index => $value) {
            if (in_array($value, [6, 9], true)) {
                $changing[] = $index + 1; // Позиции 1-6, а не 0-5
            }
        }

        return $changing;
    }

    /**
     * @param  string  $binary  Исходная бинарная строка
     * @param  list<int>  $changingLines  Позиции меняющихся линий (1-6)
     * @return string Новая бинарная строка
     */
    public function applyChangingLines(string $binary, array $changingLines): string
    {
        $chars = str_split($binary);

        foreach ($changingLines as $position) {
            $index = $position - 1; // Преобразуем 1-6 в 0-5
            if (isset($chars[$index])) {
                // Меняем 0 на 1 или наоборот
                $chars[$index] = ($chars[$index] === '1') ? '0' : '1';
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

    /**
     * @return int 2 (решка/Инь) или 3 (орёл/Ян)
     */
    private function tossCoin(): int
    {
        return random_int(self::TAILS, self::HEADS);
    }
}
