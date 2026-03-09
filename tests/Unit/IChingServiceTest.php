<?php

use App\Services\IChingService;

describe('iChingService coin casting', function (): void {
    beforeEach(function () use (&$service): void {
        $service = app(IChingService::class);
    });

    it('produces correct binary representation from coin results', function () use (&$service) {
        // 7 и 9 = Ян (1), 6 и 8 = Инь (0)
        /** @var IChingService $service */
        expect($service->coinResultsToBinary([7, 8, 9, 6, 7, 8]))->toBe('010101');
        expect($service->coinResultsToBinary([9, 9, 9, 9, 9, 9]))->toBe('111111');
        expect($service->coinResultsToBinary([6, 6, 6, 6, 6, 6]))->toBe('000000');
        expect($service->coinResultsToBinary([7, 7, 8, 8, 9, 6]))->toBe('010011');
    });

    it('identifies changing lines correctly', function () use (&$service) {
        /** @var IChingService $service */
        expect($service->getChangingLines([7, 8, 9, 6, 7, 8]))->toBe([2, 3]); // 9 и 6
        expect($service->getChangingLines([7, 7, 7, 7, 7, 7]))->toBe([]);     // нет меняющихся
        expect($service->getChangingLines([6, 6, 6, 6, 6, 6]))->toBe([0, 1, 2, 3, 4, 5]); // все 6
        expect($service->getChangingLines([7, 9, 9, 9, 9, 9]))->toBe([1, 2, 3, 4, 5]); // все 9
        expect($service->getChangingLines([7, 8, 9, 9, 6, 9]))->toBe([2, 3, 4, 5]); // 9 и 6
    });

    it('applies changing lines correctly', function () use (&$service) {
        /** @var IChingService $service */
        $secondaryBinary = $service->applyChangingLines('101010', [3, 4]);

        expect($secondaryBinary)->toBe('110010'); // Линии 3 и 4 (считая с 0) от конца меняются

        expect($service->applyChangingLines('111111', [0]))->toBe('111110');
        expect($service->applyChangingLines('000000', [5]))->toBe('100000');
        expect($service->applyChangingLines('101010', [1, 3, 5]))->toBe('000000');
    });

    it('can detect changing line', function () use (&$service) {
        /** @var IChingService $service */
        expect($service->isLineChanging(6))->toBeTrue();
        expect($service->isLineChanging(7))->toBeFalse();
        expect($service->isLineChanging(8))->toBeFalse();
        expect($service->isLineChanging(9))->toBeTrue();
    });

    it('can get line type', function () use (&$service) {
        /** @var IChingService $service */
        expect($service->getLineType(6))->toBe('old_yin');
        expect($service->getLineType(7))->toBe('young_yang');
        expect($service->getLineType(8))->toBe('young_yin');
        expect($service->getLineType(9))->toBe('old_yang');
    });

    it('returns full reading', function () use (&$service) {
        $coinResults = [6, 7, 8, 9, 8, 7];

        /** @var IChingService $service */
        $reading = $service->makeReading('The question', $coinResults);

        expect($reading['question'])->toBe('The question');
        expect($reading['coin_results'])->toBe($coinResults);
        expect($reading['binary'])->toBe('101010');
        expect($reading['secondary_binary'])->toBe('100011');
    });

});
