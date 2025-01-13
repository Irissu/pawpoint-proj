<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AppointmentStatus: string implements HasLabel, HasColor {
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Confirmed => 'Confirmada',
            self::Cancelled => 'Cancelada',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Confirmed => 'success', // green
            self::Cancelled => 'danger', // red
        };
    }
}