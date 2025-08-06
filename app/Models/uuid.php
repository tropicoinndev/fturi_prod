<?php

namespace App\Models;

use Ramsey\Uuid\Uuid as ruuid;

class uuid
{
    public static function generate()
    {
        $uuid = ruuid::uuid4();
        return strtoupper($uuid->toString());
    }
}
