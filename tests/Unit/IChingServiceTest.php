<?php

use App\Services\IChingService;

it('has basic functionality', function (): void {
    $service = app(IChingService::class);

    expect($service)->toBeInstanceOf(IChingService::class);

    $results = $service->castCoins();

    expect($results)->toBeArray();
});

describe('iChingService coin casting', function (): void {
    beforeEach(function () use (&$service): void {
        $service = app(IChingService::class);
    });

    it('generates array of 6 integers between 6 and 9', function () use (&$service): void {
        /** @var IChingService $service */
        $results = $service->castCoins();

        expect($results)->toBeArray()->toHaveCount(6);

        foreach ($results as $value) {
            expect($value)->toBeInt()->toBeBetween(6, 9);
        }
    });

    it('produces correct binary representation from coin results', function () use (&$service): void {
        // 7 и 9 = Ян (1), 6 и 8 = Инь (0)
        /** @var IChingService $service */
        expect($service->coinResultsToBinary([7, 8, 9, 6, 7, 8]))->toBe('101010');
        expect($service->coinResultsToBinary([9, 9, 9, 9, 9, 9]))->toBe('111111');
        expect($service->coinResultsToBinary([6, 6, 6, 6, 6, 6]))->toBe('000000');
        expect($service->coinResultsToBinary([7, 7, 8, 8, 9, 6]))->toBe('110010');
    });

    it('identifies changing lines correctly', function () use (&$service): void {
        /** @var IChingService $service */
        expect($service->getChangingLines([7, 8, 9, 6, 7, 8]))->toBe([3, 4]); // 9 и 6
        expect($service->getChangingLines([7, 7, 7, 7, 7, 7]))->toBe([]);     // нет меняющихся
        expect($service->getChangingLines([6, 6, 6, 6, 6, 6]))->toBe([1, 2, 3, 4, 5, 6]); // все 6
        expect($service->getChangingLines([9, 9, 9, 9, 9, 9]))->toBe([1, 2, 3, 4, 5, 6]); // все 9
        expect($service->getChangingLines([7, 8, 9, 9, 6, 9]))->toBe([3, 4, 5, 6]); // 9 и 6
    });

    it('can generate secondary hexagram from changing lines', function () use (&$service): void {
        // Первичная: 101010, меняем линии 3 и 4
        /** @var IChingService $service */
        $secondaryBinary = $service->applyChangingLines('101010', [3, 4]);

        expect($secondaryBinary)->toBe('100110');

        // Проверяем что бит меняется правильно
        expect($service->applyChangingLines('111111', [1]))->toBe('011111');
        expect($service->applyChangingLines('000000', [6]))->toBe('000001');
        expect($service->applyChangingLines('101010', [1, 3, 5]))->toBe('000000');
    });
});
