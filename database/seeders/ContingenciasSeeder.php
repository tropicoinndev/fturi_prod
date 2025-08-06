<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

#Add
use App\Models\contingencias;

class ContingenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['codigo'=>1,'valor'=>'No disponibilidad de sistema del MH',                      'estado'=>true],
            ['codigo'=>2,'valor'=>'No disponibilidad de sistema del Emisor',                  'estado'=>true],
            ['codigo'=>3,'valor'=>'Falla en el suministro de servicio de Internet del Emisor','estado'=>true],
            ['codigo'=>4,'valor'=>'Falla en el suministro de servicio de energia eléctrica del emisor que impide la transmisión de los DTE','estado'=>true],
            ['codigo'=>5,'valor'=>'Otro','estado'=>true],
        ];

        foreach($data as $d) contingencias::firstOrCreate($d);
    }
}
