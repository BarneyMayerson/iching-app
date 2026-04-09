<?php

declare(strict_types=1);

namespace App\Filament\Resources\Hexagrams\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HexagramsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('#')
                    ->numeric()
                    ->width('24px'),

                TextColumn::make('names')
                    ->label(__('Name'))
                    ->sortable(false),

                TextColumn::make('character')
                    ->label(__('Character'))
                    ->sortable(false)
                    ->extraAttributes(['class' => 'text-2xl font-serif'])
                    ->alignCenter(),

                TextColumn::make('chinese_name')
                    ->label(__('Chinese Name'))
                    ->sortable(false)
                    ->alignCenter(),

                TextColumn::make('pinyin_name')
                    ->label(__('Pinyin'))
                    ->sortable(false)
                    ->alignCenter(),

                TextColumn::make('binary')
                    ->label(__('Binary'))
                    ->fontFamily('mono')
                    ->alignCenter()
                    ->color('gray'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
