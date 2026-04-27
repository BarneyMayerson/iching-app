<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaticPages\Schemas;

use Filament\Schemas\Schema;

class StaticPageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
