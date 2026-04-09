<?php

declare(strict_types=1);

namespace App\Filament\Resources\Readings\Pages;

use App\Filament\Resources\Readings\ReadingResource;
use Filament\Resources\Pages\ManageRecords;

class ManageReadings extends ManageRecords
{
    protected static string $resource = ReadingResource::class;
}
