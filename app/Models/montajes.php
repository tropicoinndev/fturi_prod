<?php

namespace App\Models;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class montajes extends Model
{
    use HasFactory;
    protected $table = 'montajes';
    protected $appends = ['cid', 'creacion', 'modificacion'];
    public function cid(): Attribute
    {
        return Attribute::make(get: fn() => Crypt::encryptString($this->id));
    }
    public function creacion(): Attribute
    {
        return Attribute::make(get: fn() => Carbon::parse($this->created_at)->diffForHumans());
    }
    public function modificacion(): Attribute
    {
        return Attribute::make(get: fn() => Carbon::parse($this->updated_at)->diffForHumans());
    }
    public function galerias()
    {
        return $this->hasMany(montajes_galerias::class)->withCount('galerias');
    }
}
