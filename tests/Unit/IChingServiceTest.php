<?php

it('has basic functionality', function (): void {
    $service = app(IChingService::class);

    expect($service)->toBeInstanceOf(IChingService::class);

    $results = $service->castCoins();

    expect($results)->toBeArray();
});
