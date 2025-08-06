<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dte_logs extends Model
{
    use HasFactory;

    public static function lastHour()
    {
        $ultimaHora = Carbon::now()->subHour();
        return self::where("hora", '>=', $ultimaHora);
    }
}
