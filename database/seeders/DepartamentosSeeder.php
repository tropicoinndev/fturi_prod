<?php

namespace Database\Seeders;

use App\Models\departamentos;
use Illuminate\Database\Seeder;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departamentos = [
            ['codigo_postal' => '02101', 'codigo_mh' => '01', 'departamento' => 'Ahuachapán',  'paises_id' => 1],
            ['codigo_postal' => '02201', 'codigo_mh' => '02', 'departamento' => 'Santa Ana',   'paises_id' => 1],
            ['codigo_postal' => '02301', 'codigo_mh' => '03', 'departamento' => 'Sonsonate',   'paises_id' => 1],
            ['codigo_postal' => '01301', 'codigo_mh' => '04', 'departamento' => 'Chalatenango', 'paises_id' => 1],
            ['codigo_postal' => '01511', 'codigo_mh' => '05', 'departamento' => 'La Libertad', 'paises_id' => 1],
            ['codigo_postal' => '01101', 'codigo_mh' => '06', 'departamento' => 'San Salvador', 'paises_id' => 1],
            ['codigo_postal' => '01401', 'codigo_mh' => '07', 'departamento' => 'Cuscatlán',   'paises_id' => 1],
            ['codigo_postal' => '01601', 'codigo_mh' => '08', 'departamento' => 'La Paz',      'paises_id' => 1],
            ['codigo_postal' => '01201', 'codigo_mh' => '09', 'departamento' => 'Cabañas',     'paises_id' => 1],
            ['codigo_postal' => '01701', 'codigo_mh' => '10', 'departamento' => 'San Vicente', 'paises_id' => 1],
            ['codigo_postal' => '03401', 'codigo_mh' => '11', 'departamento' => 'Usulután',    'paises_id' => 1],
            ['codigo_postal' => '03301', 'codigo_mh' => '12', 'departamento' => 'San Miguel',  'paises_id' => 1],
            ['codigo_postal' => '03201', 'codigo_mh' => '13', 'departamento' => 'Morazán',     'paises_id' => 1],
            ['codigo_postal' => '03101', 'codigo_mh' => '14', 'departamento' => 'La Unión',    'paises_id' => 1],
        ];

        foreach ($departamentos as $departamento) departamentos::firstOrCreate($departamento);
    }
}
