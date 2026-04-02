<?php

declare(strict_types=1);

namespace App\Filament\Resources\Hexagrams\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Size;

class HexagramInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('General Information'))
                    ->schema([
                        TextEntry::make('number')
                            ->label('#')
                            ->inlineLabel(),

                        TextEntry::make('character')
                            ->label(__('Character'))
                            ->extraAttributes([
                                'style' => 'font-size: 3rem; line-height: 1; font-family: serif; padding-top: 0.5rem; padding-bottom: 0.5rem;',
                            ])
                            ->inlineLabel(),

                        TextEntry::make('names')
                            ->label(__('Name'))
                            ->size(Size::Large->value)
                            ->weight(FontWeight::Bold)
                            ->inlineLabel(),

                        TextEntry::make('binary')
                            ->label(__('Binary'))
                            ->fontFamily('mono')
                            ->inlineLabel(),
                    ])
                    ->columnSpanFull(),

                Section::make(__('Structure'))
                    ->schema([
                        TextEntry::make('upperTrigram.names')
                            ->label(__('Upper Trigram'))
                            ->inlineLabel(),

                        TextEntry::make('lowerTrigram.names')
                            ->label(__('Lower Trigram'))
                            ->inlineLabel(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make(__('Interpretation'))
                    ->schema([
                        TextEntry::make('chinese_name')
                            ->label(__('Chinese Name'))
                            ->inlineLabel(),

                        TextEntry::make('pinyin_name')
                            ->label(__('Pinyin'))
                            ->inlineLabel(),

                        TextEntry::make('judgment')
                            ->label(__('Judgment'))
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
