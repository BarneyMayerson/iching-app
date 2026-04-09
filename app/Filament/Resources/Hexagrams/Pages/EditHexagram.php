<?php

declare(strict_types=1);

namespace App\Filament\Resources\Hexagrams\Pages;

use App\Filament\Resources\Hexagrams\HexagramResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditHexagram extends EditRecord
{
    protected static string $resource = HexagramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }
}
