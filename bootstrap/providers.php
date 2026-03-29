<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdmPanelProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\TestingServiceProvider;

return [
    AppServiceProvider::class,
    AdmPanelProvider::class,
    FortifyServiceProvider::class,
    TestingServiceProvider::class,
];
