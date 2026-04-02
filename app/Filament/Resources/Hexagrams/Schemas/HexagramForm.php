<?php

declare(strict_types=1);

namespace App\Filament\Resources\Hexagrams\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HexagramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->required()
                    ->numeric(),
                TextInput::make('names')
                    ->required(),
                TextInput::make('chinese_name')
                    ->required(),
                TextInput::make('pinyin_name')
                    ->required(),
                TextInput::make('character')
                    ->required(),
                Select::make('upper_trigram_id')
                    ->relationship('upperTrigram', 'id')
                    ->required(),
                Select::make('lower_trigram_id')
                    ->relationship('lowerTrigram', 'id')
                    ->required(),
                TextInput::make('binary')
                    ->required(),
                TextInput::make('lines')
                    ->required(),
                TextInput::make('judgment')
                    ->required(),
            ]);
    }
}
