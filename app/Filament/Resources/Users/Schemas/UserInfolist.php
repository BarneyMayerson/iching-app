<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('User Profile'))
                    ->icon(Heroicon::UserCircle)
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('Name'))
                            ->weight(FontWeight::Bold),

                        TextEntry::make('email')
                            ->label(__('Email'))
                            ->copyable()
                            ->icon(Heroicon::Envelope),
                    ])
                    ->collapsible(),

                Section::make(__('Activity Timestamps'))
                    ->icon(Heroicon::Clock)
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('Registered'))
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->label(__('Last Profile Update'))
                            ->dateTime(),
                    ])
                    ->collapsible(),

                Section::make(__('Security & Status'))
                    ->icon(Heroicon::ShieldCheck)
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('email_verified_at')
                            ->label(__('Email Verified'))
                            ->boolean()
                            ->alignCenter()
                            ->getStateUsing(fn ($record) => $record->email_verified_at !== null),

                        IconEntry::make('two_factor_confirmed_at')
                            ->label(__('2FA Enabled'))
                            ->boolean()
                            ->alignCenter()
                            ->getStateUsing(fn ($record) => $record->two_factor_confirmed_at !== null),

                        TextEntry::make('id')
                            ->label('ID')
                            ->alignCenter()
                            ->fontFamily('mono'),
                    ]),
            ]);
    }
}
