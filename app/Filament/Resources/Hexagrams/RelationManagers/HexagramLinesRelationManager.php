<?php

declare(strict_types=1);

namespace App\Filament\Resources\Hexagrams\RelationManagers;

use App\Models\Line;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HexagramLinesRelationManager extends RelationManager
{
    protected static string $relationship = 'hexagramLines';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getInfoSection(),
                self::getMeaningsSection(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('position')
            ->heading(__('Lines'))
            ->columns([
                TextColumn::make('position')
                    ->label(__('Position'))
                    ->searchable(false)
                    ->sortable(false)
                    ->alignCenter(),

                TextColumn::make('type')
                    ->label(__('Type'))
                    ->state(function (Line $record) {
                        $binary = $record->hexagram->binary;
                        $lineValue = $binary[6 - $record->position] ?? null;

                        return self::getLineSymbol($lineValue);
                    })
                    ->searchable(false)
                    ->sortable(false)
                    ->alignCenter(),

                TextColumn::make('meaning')
                    ->label(__('Meaning'))
                    ->searchable(false)
                    ->sortable(false)
                    ->limit(100)
                    ->wrap(),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading(function (Line $record) {
                        $binary = $record->hexagram->binary;
                        $lineValue = $binary[6 - $record->position] ?? null;
                        $symbol = self::getLineSymbol($lineValue);

                        return __('Edit Line :position', ['position' => $record->position])." [$symbol]";
                    })
                    ->closeModalByClickingAway(false),
            ]);
    }

    private static function getLineSymbol(?string $value): string
    {
        return $value === '1' ? '―' : '- -';
    }

    private static function getInfoSection(): Section
    {
        return Section::make()
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                TextInput::make('position')
                    ->label(__('Position'))
                    ->disabled(),

                TextEntry::make('line_type')
                    ->label(__('Type'))
                    ->state(function (Line $record) {
                        $binary = $record->hexagram->binary;
                        $lineValue = $binary[6 - $record->position] ?? null;

                        return self::getLineSymbol($lineValue);
                    }),
            ]);
    }

    private static function getMeaningsSection(): Section
    {
        return Section::make(__('Meanings'))
            ->description(__('Translation of the line\'s aphorism.'))
            ->columns(2)
            ->columnSpanFull()
            ->schema([
                Textarea::make('meaning.en')
                    ->label(__('Meaning (EN)'))
                    ->rows(4)
                    ->required(),
                Textarea::make('meaning.ru')
                    ->label(__('Meaning (RU)'))
                    ->rows(4)
                    ->required(),
            ]);
    }
}
