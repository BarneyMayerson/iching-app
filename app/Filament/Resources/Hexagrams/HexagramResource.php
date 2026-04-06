<?php

declare(strict_types=1);

namespace App\Filament\Resources\Hexagrams;

use App\Filament\Resources\Hexagrams\Pages\CreateHexagram;
use App\Filament\Resources\Hexagrams\Pages\EditHexagram;
use App\Filament\Resources\Hexagrams\Pages\ListHexagrams;
use App\Filament\Resources\Hexagrams\Pages\ViewHexagram;
use App\Filament\Resources\Hexagrams\RelationManagers\HexagramLinesRelationManager;
use App\Filament\Resources\Hexagrams\Schemas\HexagramForm;
use App\Filament\Resources\Hexagrams\Schemas\HexagramInfolist;
use App\Filament\Resources\Hexagrams\Tables\HexagramsTable;
use App\Models\Hexagram;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class HexagramResource extends Resource
{
    use Translatable;

    protected static ?string $model = Hexagram::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Sparkles;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('I-Ching');
    }

    public static function form(Schema $schema): Schema
    {
        return HexagramForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HexagramInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HexagramsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            HexagramLinesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHexagrams::route('/'),
            'create' => CreateHexagram::route('/create'),
            'view' => ViewHexagram::route('/{record}'),
            'edit' => EditHexagram::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('Hexagrams');
    }

    public static function getModelLabel(): string
    {
        return __('Hexagram');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Hexagrams');
    }
}
