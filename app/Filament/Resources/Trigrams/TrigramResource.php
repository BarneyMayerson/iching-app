<?php

declare(strict_types=1);

namespace App\Filament\Resources\Trigrams;

use App\Filament\Resources\Trigrams\Pages\ManageTrigrams;
use App\Models\Trigram;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TrigramResource extends Resource
{
    protected static ?string $model = Trigram::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // public static function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             TextInput::make('number')
    //                 ->required()
    //                 ->numeric(),
    //             TextInput::make('names')
    //                 ->required(),
    //             TextInput::make('chinese_name')
    //                 ->required(),
    //             TextInput::make('pinyin_name')
    //                 ->required(),
    //             TextInput::make('character')
    //                 ->required(),
    //             TextInput::make('attribute')
    //                 ->required(),
    //             TextInput::make('images')
    //                 ->required(),
    //             FileUpload::make('chinese_image')
    //                 ->image()
    //                 ->required(),
    //             FileUpload::make('pinyin_image')
    //                 ->image()
    //                 ->required(),
    //             TextInput::make('family_relationship')
    //                 ->required(),
    //             TextInput::make('binary')
    //                 ->required(),
    //             TextInput::make('lines')
    //                 ->required(),
    //         ]);
    // }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Static Information'))
                    ->description(__('This information is static and cannot be changed. It is only displayed for reference.'))
                    ->icon(Heroicon::LockClosed)
                    ->columns(3)
                    ->schema([
                        TextInput::make('number')
                            ->label('#')
                            ->disabled(),
                        TextInput::make('character')
                            ->label(__('Character'))
                            ->disabled(),
                        TextInput::make('binary')
                            ->label(__('Binary'))
                            ->disabled(),
                    ]),

                Section::make(__('Translation & Interpretation'))
                    ->description(__('Editable content for all locales.'))
                    ->icon(Heroicon::PencilSquare)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TagsInput::make('names.en')
                                    ->label(__('Names in English'))
                                    ->placeholder(__('Add a name and press enter'))
                                    ->required(),
                                TagsInput::make('names.ru')
                                    ->label(__('Names in Russian'))
                                    ->placeholder(__('Add a name and press enter'))
                                    ->required(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('attribute.en')
                                    ->label(__('Attribute (EN)'))
                                    ->required(),
                                TextInput::make('attribute.ru')
                                    ->label(__('Attribute (RU)'))
                                    ->required(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('images.en')
                                    ->label(__('Symbolic Image (EN)'))
                                    ->required(),
                                TextInput::make('images.ru')
                                    ->label(__('Symbolic Image (RU)'))
                                    ->required(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('family_relationship.en')
                                    ->label(__('Family Role (EN)'))
                                    ->required(),
                                TextInput::make('family_relationship.ru')
                                    ->label(__('Family Role (RU)'))
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Основная информация')
                    ->icon(Heroicon::InformationCircle)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('number')
                                    ->numeric()
                                    ->label('#')
                                    ->inlineLabel()
                                    ->badge()
                                    ->color('success'),
                                TextEntry::make('character')
                                    ->label(__('Character'))
                                    ->inlineLabel(),
                                TextEntry::make('binary')
                                    ->label(__('Binary'))
                                    ->inlineLabel(),
                                TextEntry::make('lines')
                                    ->label(__('Lines'))
                                    ->inlineLabel(),
                                TextEntry::make('chinese_name')
                                    ->label(__('Chinese Name'))
                                    ->inlineLabel(),
                                TextEntry::make('pinyin_name')
                                    ->label(__('Pinyin'))
                                    ->inlineLabel(),
                                TextEntry::make('chinese_image')
                                    ->label(__('Chinese Image'))
                                    ->inlineLabel(),
                                TextEntry::make('pinyin_image')
                                    ->label(__('Pinyin Image'))
                                    ->inlineLabel(),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Контекст')
                    ->icon(Heroicon::Link)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('attribute')
                                    ->label(__('Attribute')),
                                TextEntry::make('family_relationship')
                                    ->label(__('Family Relationship'))
                                    ->formatStateUsing(fn ($state) => $state ?: '—'),
                                TextEntry::make('images')
                                    ->label(__('Image'))
                                    ->listWithLineBreaks()
                                    ->bulleted(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('#')
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('names')
                    ->label(__('Name'))
                    ->sortable(false),
                TextColumn::make('attribute')
                    ->label(__('Attribute'))
                    ->sortable(false),
                TextColumn::make('images')
                    ->label(__('Image'))
                    ->sortable(false),
                TextColumn::make('family_relationship')
                    ->label(__('Family Relationship'))
                    ->sortable(false)
                    ->toggledHiddenByDefault(),
                TextColumn::make('character')
                    ->label(__('Character'))
                    ->searchable(false)
                    ->sortable(false)
                    ->alignCenter(),
                TextColumn::make('binary')
                    ->label(__('Binary'))
                    ->fontFamily('mono')
                    ->alignCenter()
                    ->color('gray'),

                TextColumn::make('chinese_name')
                    ->label(__('Chinese Name'))
                    ->searchable(false)
                    ->alignCenter()
                    ->toggledHiddenByDefault(),
                TextColumn::make('pinyin_name')
                    ->label(__('Pinyin'))
                    ->searchable(false)
                    ->alignCenter()
                    ->toggledHiddenByDefault(),
                TextColumn::make('chinese_image')
                    ->label(__('Chinese Image'))
                    ->searchable(false)
                    ->alignCenter()
                    ->toggledHiddenByDefault(),
                TextColumn::make('pinyin_image')
                    ->label(__('Pinyin Image'))
                    ->searchable(false)
                    ->alignCenter()
                    ->toggledHiddenByDefault(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    // ->slideOver()
                    ->closeModalByClickingAway(false),
                EditAction::make()
                    // ->slideOver()
                    ->closeModalByClickingAway(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTrigrams::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('Trigrams');
    }

    public static function getModelLabel(): string
    {
        return __('Trigram');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Trigrams');
    }
}
