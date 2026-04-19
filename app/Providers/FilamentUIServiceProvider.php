<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class FilamentUIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Table::configureUsing(function (Table $table) {
            $table
                ->reorderableColumns()
                ->columnManagerColumns(2)
                ->columnManagerTriggerAction(fn (Action $action) => $action->button()->label(__('Columns')))
                ->paginationPageOptions([10, 25, 50]);
        });

        Column::configureUsing(function (Column $column) {
            $column->toggleable();
        });

        TextColumn::configureUsing(function (TextColumn $column) {
            $column
                ->sortable()
                ->searchable();
        });

        ViewAction::configureUsing(function (ViewAction $action) {
            $action->iconButton();
        });

        EditAction::configureUsing(function (EditAction $action) {
            $action->iconButton();
        });

        DeleteAction::configureUsing(function (DeleteAction $action) {
            $action->iconButton();
        });
    }
}
