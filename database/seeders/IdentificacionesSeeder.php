<?php

namespace Database\Seeders;

use App\Models\identificaciones;
use Illuminate\Database\Seeder;

class IdentificacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $identificaciones = [
            //Clientes jurídicos
            ['identificacion' => 'NIT o DUI', 'codigo' => '36', 'regex' => '^([0-9]{14}|[0-9]{9})$', 'info' => 'Ingrese el NIT sin guiones (00000000000000 o 00000000)', 'tipo_cliente' => false, 'estado' => true],

            //Clientes naturales
            ['identificacion' => 'DUI', 'codigo' => '13', 'regex' => '^[0-9]{8}-[0-9]{1}$', 'info' => 'Ingrese el numero de DUI (00000000-0)', 'tipo_cliente' => true, 'estado' => true],
            ['identificacion' => 'NIT', 'codigo' => '36', 'regex' => '^([0-9]{14}|[0-9]{9})$', 'info' => 'Ingrese el NIT sin guiones', 'tipo_cliente' => true, 'estado' => true],
            ['identificacion' => 'Pasaporte', 'codigo' => '03', 'regex' => '^[A-Za-z0-9]{6,9}$', 'info' => 'Alfanumérico de 6 - 9 caracteres', 'tipo_cliente' => true, 'estado' => true],
            ['identificacion' => 'Carnet de Residente (SV)', 'codigo' => '02', 'regex' => '^[0-9]{9,13}$', 'info' => 'Numero sin guiones de 9 a 13 caracteres', 'tipo_cliente' => true, 'estado' => true],
            ['identificacion' => 'Otro', 'codigo' => '37', 'regex' => '^[A-Za-z0-9]{3,15}$', 'info' => 'Alfanumérico de 3 - 15 caracteres', 'tipo_cliente' => true, 'estado' => true],

        ];

        foreach ($identificaciones as $identificacion)
            identificaciones::firstOrCreate($identificacion);
    }
}
