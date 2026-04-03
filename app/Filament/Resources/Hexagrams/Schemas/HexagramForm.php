<?php

declare(strict_types=1);

namespace App\Filament\Resources\Hexagrams\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class HexagramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Static Information'))
                    ->description(__('This information is static and cannot be changed. It is only displayed for reference.'))
                    ->icon(Heroicon::LockClosed)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('number')
                            ->label('#')
                            ->inlineLabel()
                            ->color('gray'),
                        TextEntry::make('character')
                            ->label(__('Character'))
                            ->inlineLabel()
                            ->color('gray'),
                        TextEntry::make('binary')
                            ->label(__('Binary'))
                            ->inlineLabel()
                            ->color('gray'),

                        TextEntry::make('chinese_name')
                            ->label(__('Chinese Name'))
                            ->inlineLabel()
                            ->color('gray'),
                        TextEntry::make('pinyin_name')
                            ->label(__('Pinyin'))
                            ->inlineLabel()
                            ->color('gray'),
                        TextEntry::make('lines')
                            ->label(__('Lines'))
                            ->inlineLabel()
                            ->color('gray'),
                    ]),

                Section::make(__('Translation & Interpretation'))
                    ->description(__('Editable content for all locales.'))
                    ->icon(Heroicon::PencilSquare)
                    ->schema([
                        Section::make(__('Names'))
                            ->description(__('The first name in the list will be used as the primary name for the hexagram.'))
                            ->schema([
                                TagsInput::make('names.en')
                                    ->label(__('Names in English'))
                                    ->placeholder(__('Add a name and press enter'))
                                    ->required(),
                                TagsInput::make('names.ru')
                                    ->label(__('Names in Russian'))
                                    ->placeholder(__('Add a name and press enter'))
                                    ->required(),
                            ])
                            ->columns(2)
                            ->icon(Heroicon::Hashtag)
                            ->collapsible(),

                        Section::make(__('Judgment'))
                            ->description(__('The judgment is the main interpretation of the hexagram. It should be a detailed explanation of the hexagram\'s meaning and significance.'))
                            ->collapsible()
                            ->schema([
                                Textarea::make('judgment.en')
                                    ->label(__('Judgment in English'))
                                    ->rows(5)
                                    ->required(),
                                Textarea::make('judgment.ru')
                                    ->label(__('Judgment in Russian'))
                                    ->rows(5)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->icon(Heroicon::Scale)
                            ->collapsible(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ]);

    }
}
