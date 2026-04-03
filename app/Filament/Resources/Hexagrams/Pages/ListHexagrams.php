<?php

declare(strict_types=1);

namespace App\Filament\Resources\Hexagrams\Pages;

use App\Filament\Resources\Hexagrams\HexagramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHexagrams extends ListRecords
{
    protected static string $resource = HexagramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
