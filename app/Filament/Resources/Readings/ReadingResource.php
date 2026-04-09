<?php

declare(strict_types=1);

namespace App\Filament\Resources\Readings;

use App\Filament\Resources\Readings\Pages\ManageReadings;
use App\Models\Hexagram;
use App\Models\Reading;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReadingResource extends Resource
{
    protected static ?string $model = Reading::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Reading')
                    ->columns(2)
                    ->tabs([
                        self::getReadingDetailsTab(),
                        self::getProcessTab(),
                    ]),
                Section::make(__('Hexagrams'))
                    ->icon(Heroicon::BookOpen)
                    ->collapsed()
                    ->schema([
                        self::getPrimaryHexagramSection(),
                        self::getSecondaryHexagramSection(),
                    ]),
            ]);
    }

    private static function getReadingDetailsTab(): Tabs\Tab
    {
        return Tabs\Tab::make(__('Main'))
            ->icon(Heroicon::DocumentText)
            ->schema([
                TextEntry::make('user.name')->label(__('User')),
                TextEntry::make('created_at')->label(__('Date'))->dateTime(),
                TextEntry::make('question')->label(__('Question')),
            ]);
    }

    private static function getProcessTab(): Tabs\Tab
    {
        return Tabs\Tab::make(__('Process'))
            ->icon(Heroicon::CpuChip)
            ->schema([
                TextEntry::make('coin_results')
                    ->label(__('Coin Toss Results'))
                    ->listWithLineBreaks()
                    ->bulleted(),
            ]);
    }

    private static function getPrimaryHexagramSection(): Section
    {
        return Section::make(__('Hexagram'))
            ->schema([
                TextEntry::make('hexagram_name')
                    ->label(__('Name'))
                    ->state(fn (Reading $record): string => self::formatHexagramName($record->hexagram)),
                TextEntry::make('hexagram.judgment')
                    ->label(__('Judgment')),
            ]);
    }

    private static function getSecondaryHexagramSection(): Section
    {
        return Section::make(__('Secondary Hexagram'))
            ->visible(fn (Reading $record): bool => filled($record->secondary_binary))
            ->schema([
                TextEntry::make('secondary_hexagram_name')
                    ->label(__('Name'))
                    ->state(fn (Reading $record): string => self::formatHexagramName($record->secondaryHexagram)),
                TextEntry::make('secondaryHexagram.judgment')
                    ->label(__('Judgment')),
            ]);
    }

    private static function formatHexagramName(?Hexagram $hexagram): string
    {
        if (! $hexagram) {
            return __('Unknown');
        }

        $name = $hexagram->names[0] ?? '—';

        return "{$name} ({$hexagram->character})";
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime(),
                TextColumn::make('user.name')
                    ->label(__('User'))
                    ->url(fn (Reading $record): string => self::getUrl(parameters: ['filters[user][value]' => $record->user_id]))
                    ->color('primary'),
                TextColumn::make('question')
                    ->label(__('Question'))
                    ->limit(50),
                TextColumn::make('hexagram.name')
                    ->label(__('Hexagram'))
                    ->searchable(false)
                    ->sortable(false)
                    ->state(fn (Reading $record): string => self::formatHexagramName($record->hexagram)),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->label(__('User'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageReadings::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('Readings');
    }

    public static function getModelLabel(): string
    {
        return __('Reading');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Readings');
    }
}
