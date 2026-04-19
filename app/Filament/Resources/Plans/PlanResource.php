<?php

declare(strict_types=1);

namespace App\Filament\Resources\Plans;

use App\Filament\Resources\Plans\Pages\ManagePlans;
use App\Models\Plan;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class PlanResource extends Resource
{
    use Translatable;

    protected static ?string $model = Plan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    public static function getNavigationGroup(): ?string
    {
        return __('Site Settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('Plans');
    }

    public static function getModelLabel(): string
    {
        return __('Plan');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Plans');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                self::getMainSettingsViewSection(),
                self::getLimitsViewSection(),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                self::getMainSettingsFormSection(),
                self::getLimitsFormSection(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name')),
                TextColumn::make('slug')
                    ->label(__('Slug')),
                TextColumn::make('daily_readings_limit')
                    ->label(__('Daily Readings'))
                    ->numeric(),
                TextColumn::make('daily_interpretations_limit')
                    ->label(__('Daily Interpretations'))
                    ->numeric(),
                TextColumn::make('price_cents')
                    ->label(__('Price Cents'))
                    ->numeric(),
                IconColumn::make('is_custom_price')
                    ->label(__('Custom Price'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->iconButton()
                    ->closeModalByClickingAway(false),
                EditAction::make()
                    ->iconButton()
                    ->closeModalByClickingAway(false),
                DeleteAction::make()
                    ->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePlans::route('/'),
        ];
    }

    private static function getMainSettingsViewSection(): Section
    {
        return Section::make(__('Main Settings'))
            ->icon(Heroicon::Cog)
            ->columns(2)
            ->schema([
                TextEntry::make('name')
                    ->label(__('Name'))
                    ->inlineLabel(),
                TextEntry::make('slug')
                    ->label(__('Slug'))
                    ->inlineLabel(),
                TextEntry::make('price_cents')
                    ->label(__('Price (USD)'))
                    ->inlineLabel()
                    ->numeric()
                    ->prefix('$')
                    ->dehydrateStateUsing(fn ($state) => (int) ($state * 100))
                    ->formatStateUsing(fn ($state) => $state / 100),
                Toggle::make('is_custom_price')
                    ->label(__('Custom Price'))
                    ->inlineLabel(),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->columnSpanFull(),
            ])
            ->columnSpanFull();
    }

    private static function getLimitsViewSection(): Section
    {
        return Section::make(__('Limits'))
            ->icon(Heroicon::Scale)
            ->columns(2)
            ->schema([
                TextEntry::make('daily_readings_limit')
                    ->label(__('Daily Readings'))
                    ->inlineLabel()
                    ->numeric(),
                TextEntry::make('daily_interpretations_limit')
                    ->label(__('Daily Interpretations'))
                    ->inlineLabel()
                    ->numeric(),
            ])
            ->columnSpanFull();
    }

    private static function getMainSettingsFormSection(): Section
    {
        return Section::make(__('Main Settings'))
            ->icon(Heroicon::Cog)
            ->columns(2)
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('price_cents')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_custom_price')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ])
            ->columnSpanFull();
    }

    private static function getLimitsFormSection(): Section
    {
        return Section::make(__('Limits'))
            ->icon(Heroicon::Scale)
            ->columns(2)
            ->schema([
                TextInput::make('daily_readings_limit')
                    ->label(__('Daily Readings'))
                    ->required()
                    ->numeric()
                    ->default(5),
                TextInput::make('daily_interpretations_limit')
                    ->label(__('Daily Interpretations'))
                    ->required()
                    ->numeric()
                    ->default(2),
            ])
            ->columnSpanFull();
    }
}
