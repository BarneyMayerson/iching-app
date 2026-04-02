<?php

namespace App\Filament\Resources\Hexagrams\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HexagramInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('number')
                    ->numeric(),
                TextEntry::make('chinese_name'),
                TextEntry::make('pinyin_name'),
                TextEntry::make('character'),
                TextEntry::make('upperTrigram.id')
                    ->label('Upper trigram'),
                TextEntry::make('lowerTrigram.id')
                    ->label('Lower trigram'),
                TextEntry::make('binary'),
            ]);
    }
}
