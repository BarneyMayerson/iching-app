<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaticPages\Schemas;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaticPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make(__('Common stuff'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('slug')
                            ->inlineLabel()
                            ->required(),
                        Toggle::make('is_published')
                            ->required(),
                    ])
                    ->columnSpanFull(),

                Section::make(__('Translation'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('title.en')
                            ->label(__('Title in English'))
                            ->required(),
                        TextInput::make('title.ru')
                            ->label(__('Title in Russian'))
                            ->required(),
                        MarkdownEditor::make('content.en')
                            ->label(__('Content in English'))
                            ->required(),
                        MarkdownEditor::make('content.ru')
                            ->label(__('Content in Russian'))
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
