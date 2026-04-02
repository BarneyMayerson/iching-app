<?php

namespace App\Filament\Resources\Hexagrams\Pages;

use App\Filament\Resources\Hexagrams\HexagramResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHexagram extends ViewRecord
{
    protected static string $resource = HexagramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
