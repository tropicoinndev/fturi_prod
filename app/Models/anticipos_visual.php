<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class anticipos_visual extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'anticipos';
    public $timestamps = false;
}
