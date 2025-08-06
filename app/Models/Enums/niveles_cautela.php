<?php

namespace App\Models\Enums;

use Illuminate\Support\Facades\Crypt;

enum  niveles_cautela: int
{
    case SIN_RIESGO = 0;
    case RIESGO_BAJO = 1;
    case RIESGO_MEDIO = 2;
    case RIESGO_ALTO = 3;

    public function value(): string
    {
        return match ($this) {

            self::SIN_RIESGO => 'Sin etiquetar',
            self::RIESGO_BAJO => 'Riesgo bajo',
            self::RIESGO_MEDIO => 'Riesgo medio',
            self::RIESGO_ALTO => 'Riesgo alto',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SIN_RIESGO => 'light',
            self::RIESGO_BAJO => 'success',
            self::RIESGO_MEDIO => 'warning',
            self::RIESGO_ALTO => 'danger',
        };
    }



    public static function getAll(): array
    {
        return array_map(function ($level) {
            return [
                'id' => $level->value,
                'value' => $level->value(),
                'color' => $level->color(),
            ];
        }, self::cases());
    }
}
