<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class tipo_mantenimientos extends Model
{
    use HasFactory;
    protected $appends = ['duracion'];

    protected function duracion(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->formatearDuracion($this->duracion_promedio)
        );
    }
    private function formatearDuracion(string $duracionPromedio): string
    {
        $duracion = Carbon::parse($duracionPromedio);
        $horas = $duracion->hour;
        $minutos = $duracion->minute;
        $resultado = [];

        if ($horas > 0) {
            $resultado[] = $horas . ' ' . Str::plural('hora', $horas);
        }

        if ($minutos > 0) {
            $resultado[] = $minutos . ' ' . Str::plural('minuto', $minutos);
        }

        return !empty($resultado) ? implode(' ', $resultado) : '0 minutos';
    }

    public function inicio_habitaciones(){
        return $this->belongsTo(estado_habitaciones::class,'estado_habitacion_inicio_id');
    }
    public function completado_habitaciones(){
        return $this->belongsTo(estado_habitaciones::class,'estado_habitacion_completado_id');
    }
    public function usuario()
    {
        return $this->belongsToMany(User::class, 'tipo_mantenimientos_users', 'tipo_mantenimientos_id', 'users_id');
    }

}
