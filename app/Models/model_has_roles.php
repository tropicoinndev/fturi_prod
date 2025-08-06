<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class model_has_roles extends Model
{
    use HasFactory;
    protected $table = "model_has_roles";
    public $timestamps = false;
    public $incrementing = false;

    public function users()
    {
        return $this->belongsTo(User::class, 'model_id');
    }
    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
