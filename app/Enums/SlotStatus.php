<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SlotStatus: string implements HasLabel, HasColor {
    case Available = 'available';
    case Booked = 'booked';

    public function getLabel(): string
    {
        return match ($this) {
            self::Available => 'Disponible',
            self::Booked => 'Reservado',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Available => 'success', // green
            self::Booked => 'danger', // red
        };
    }
}
