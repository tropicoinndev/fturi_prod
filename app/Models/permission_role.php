<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class permission_role extends Model
{
    use HasFactory;
    protected $table = "role_has_permissions";
    public $timestamps = false;
    public $incrementing = false;
    public function permissions()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
