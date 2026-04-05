<?php

declare(strict_types=1);

namespace App\Filament\Resources\Trigrams\Pages;

use App\Filament\Resources\Trigrams\TrigramResource;
use Filament\Resources\Pages\ManageRecords;

class ManageTrigrams extends ManageRecords
{
    protected static string $resource = TrigramResource::class;
}
