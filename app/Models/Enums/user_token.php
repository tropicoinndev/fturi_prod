<?php

namespace App\Models\Enums;

enum  user_token: int
{
    case VENDEDOR = 3001;
    case PRODUCCION = 3002;
    case MANTENIMIENTO = 3003;


    public function label(): string
    {
        return match ($this) {

            self::VENDEDOR => 'VENDEDOR DE HABITACIONES',
            self::PRODUCCION => 'PRODUCCIÓN COCINA O BAR',
            self::MANTENIMIENTO => 'MANTENIMIENTOS',
        };
    }

    public static function getAll(): array
    {
        return array_map(function ($level) {
            return [
                'id' => $level->value,
                'label' => $level->label(),
            ];
        }, self::cases());
    }
}
