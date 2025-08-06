<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;


class mantenimientos extends Model
{
    use HasFactory;
    protected $fillable = [
        'asignado_users_id',
    ];
    protected $appends = ['cid', 'creacion', 'modificacion','transcurrido','confirmaciontime','asignatime'];
    public function cid(): Attribute
    {
        return Attribute::make(get: fn () => Crypt::encryptString($this->id));
    }
    public function creacion(): Attribute
    {
        return Attribute::make(get: fn () => Carbon::parse($this->created_at)->diffForHumans());
    }
    public function modificacion(): Attribute
    {
        return Attribute::make(get: fn () => Carbon::parse($this->updated_at)->diffForHumans());
    }
    public function tipo_mantenimientos()
    {
        return $this->belongsTo(tipo_mantenimientos::class, 'tipo_mantenimientos_id');
    }
    public function habitaciones()
    {
        return $this->belongsTo(habitaciones::class, 'habitaciones_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function creador()
    {
        return $this->belongsTo(User::class, 'creacion_users_id');
    }
    public function asignado()
    {
        return $this->belongsTo(User::class, 'asignado_users_id');
    }
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_users_id');
    }
    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
    }
    public function transcurrido(): Attribute
    {
        return Attribute::make(

            get: fn () => $this->attributes['finalizacion'] ? Carbon::parse($this->attributes['finalizacion'])->diffForHumans($this->attributes['inicio']) : 'Aun sin finalizar'
        );
    }
    public function confirmaciontime(): Attribute
    {
        return Attribute::make(

            get: fn () => $this->attributes['confirmacion_asignacion'] ? Carbon::parse($this->attributes['confirmacion_asignacion'])->diffForHumans($this->attributes['asignacion']) : 'Aun sin confirmar'
        );
    }
    public function asignatime(): Attribute
    {
        return Attribute::make(

            get: fn () => $this->attributes['asignacion'] ? Carbon::now()->diffForHumans($this->attributes['asignacion']) : 'Sin asignar'
        );
    }

}
