<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name')),

                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('Registered'))
                    ->dateTime(),

                TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('email_verified_at')
                    ->label(__('Email Verified'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('two_factor_confirmed_at')
                    ->label(__('Two-Factor Authenticated'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([

                Action::make('toggleEmailVerify')
                    ->label(fn (User $record): string => $record->email_verified_at
                        ? __('Unverify Email')
                        : __('Verify Email')
                    )
                    ->icon(fn (User $record) => $record->email_verified_at
                        ? Heroicon::XCircle
                        : Heroicon::CheckBadge
                    )
                    ->color(fn (User $record): string => $record->email_verified_at
                        ? 'danger'
                        : 'success'
                    )
                    ->action(function (User $record) {
                        $record->update(['email_verified_at' => $record->email_verified_at
                            ? null
                            : now(),
                        ]);

                        Notification::make()
                            ->title($record->email_verified_at
                                ? __('Email Verified')
                                : __('Verification Removed'))
                            ->success()
                            ->send();
                    }),

                Action::make('toggle2FA')
                    ->label(fn (User $record): string => $record->two_factor_confirmed_at
                        ? __('Disable 2FA')
                        : __('Enable 2FA')
                    )
                    ->icon(Heroicon::ShieldCheck)
                    ->color(fn (User $record): string => $record->two_factor_confirmed_at
                        ? 'danger'
                        : 'gray'
                    )
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->update(['two_factor_confirmed_at' => $record->two_factor_confirmed_at
                            ? null
                            : now(),
                        ]);
                    }),

                ViewAction::make()
                    ->modalHeading(fn (User $record) => __('View User').": {$record->name}")
                    ->closeModalByClickingAway(false),
                EditAction::make()
                    ->modalHeading(fn (User $record) => __('Edit User').": {$record->name}")
                    ->closeModalByClickingAway(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('verifyEmails')
                        ->label(__('Verify Selected Emails'))
                        ->icon(Heroicon::CheckBadge)
                        ->action(fn (Collection $records) => $records->each->update(['email_verified_at' => now()]))
                        ->deselectRecordsAfterCompletion()
                        ->color('success'),

                    BulkAction::make('unverifyEmails')
                        ->label(__('Unverify Selected Emails'))
                        ->icon(Heroicon::XCircle)
                        ->action(fn (Collection $records) => $records->each->update(['email_verified_at' => null]))
                        ->deselectRecordsAfterCompletion()
                        ->color('danger'),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
