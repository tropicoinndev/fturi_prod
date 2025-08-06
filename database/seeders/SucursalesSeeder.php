<?php

namespace Database\Seeders;

use App\Models\sucursales;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SucursalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $data = [
            [#Trópico Inn

                'sucursal'     =>'Hotel Trópico Inn',
                'direccion'    =>'CP 3301, Av. Roosevelt Sur, San Miguel',
                'telefono'     =>'+503 2682100',
                'correo'       =>'tropico@tropicoinn.com.sv',
                'nit'          =>'12170509850014',
                'nrc'          =>'90670',
                'iva'          =>true,
                'giro'         =>'Hosteleria y Turismo',
                'matriz'       =>true,
                'municipios_id'=>215,#San Miguel
                'codigo_establecimiento'=>'02',
            ],
            [#Trpiclub
                
                'sucursal'     =>'Tropiclub',
                'direccion'    =>'Playa el Cuco, Intipucá, La Unión',
                'telefono'     =>'+503 2682100',
                'correo'       =>'tropiclub@tropicoinn.com.sv',
                'nit'          =>'12170509850014',
                'nrc'          =>'90670',
                'iva'          =>true,
                'giro'         =>'Hosteleria y Turismo',
                'matriz'       =>false,
                'municipios_id'=>251,#Intipucá
                'codigo_establecimiento'=>'01',
            ],
        ];

        foreach($data as $d) sucursales::firstOrCreate($d);
    }
}
