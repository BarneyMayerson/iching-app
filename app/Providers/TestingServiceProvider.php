<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia;

class TestingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (! $this->app->runningUnitTests()) {
            return;
        }

        AssertableInertia::macro('hasResource', function (string $key, JsonResource $resource) {
            /** @var AssertableInertia $this */
            $this->has($key);

            // Используем phpstan-ignore-line, так как макросы Laravel
            // связываются с объектом и в рантайме имеют доступ к protected методам.
            // Это специфика работы Macroable трейта.
            expect($this->prop($key))->toEqual($resource->response()->getData(true)); // @phpstan-ignore-line

            return $this;
        });

        AssertableInertia::macro('hasPaginatedResource', function (string $key, ResourceCollection $resource) {
            /** @var AssertableInertia $this */
            expect($this->prop($key))->toHaveKeys(['data', 'meta', 'links']); // @phpstan-ignore-line
            //
            // Сравниваем ТОЛЬКО данные (data)
            $resourceArray = $resource->response()->getData(true);
            expect($this->prop("{$key}.data"))->toEqual($resourceArray['data']); // @phpstan-ignore-line

            return $this;
        });

        TestResponse::macro('assertComponentIs', function (string $component, bool $shouldExit = true) {
            /** @var TestResponse<\Illuminate\Http\Response> $this */
            return $this->assertInertia(
                fn (AssertableInertia $inertia) => $inertia->component(
                    $component,
                    $shouldExit
                )
            );
        });

        TestResponse::macro('assertHasResource', function (string $key, JsonResource $resource) {
            /** @var TestResponse<\Illuminate\Http\Response> $this */
            return $this->assertInertia(
                fn (AssertableInertia $inertia) => $inertia->hasResource($key, $resource) // @phpstan-ignore-line
            );
        });

        TestResponse::macro('assertHasPaginatedResource', function (string $key, ResourceCollection $resource) {
            /** @var TestResponse<\Illuminate\Http\Response> $this */
            return $this->assertInertia(fn (AssertableInertia $inertia) => $inertia->hasPaginatedResource($key, $resource)); // @phpstan-ignore-line
        });
    }
}
