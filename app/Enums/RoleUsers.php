<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RoleUsers: string implements HasLabel, HasColor {
    case Admin = 'admin';
    case Vet = 'vet';
    case User = 'owner';

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Vet => 'Veterinario',
            self::User => 'Dueño',
        };

    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Admin => 'warning', // amber
            self::Vet => 'success', // green
            self::User => 'info', // blue
        };
    }

}