<?php

declare(strict_types=1);

namespace App\Enums\Reading;

use App\Enums\UseValueAsLabel;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InterpretationStatus: string implements HasColor, HasLabel
{
    use UseValueAsLabel;

    case NOT_STARTED = 'Not started';
    case PENDING = 'Pending';
    case PROCESSING = 'Processing';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Cancelled';
    case FAILED = 'Failed';

    public function getColor(): array
    {
        return match ($this) {
            self::NOT_STARTED => Color::Gray,
            self::PENDING => Color::Lime,
            self::PROCESSING => Color::Blue,
            self::COMPLETED => Color::Green,
            self::CANCELLED => Color::Yellow,
            self::FAILED => Color::Red,
        };
    }
}
