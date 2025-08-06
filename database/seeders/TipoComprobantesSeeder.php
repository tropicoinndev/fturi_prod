<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

#Add
use App\Models\tipo_comprobantes;

class TipoComprobantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoComprobantes = [
            ['tipo' => 'Consumidor Final',  'codigo' => '01', 'token' => '7002'],
            ['tipo' => 'Crédito Fiscal',    'codigo' => '03', 'token' => '7001'],
            ['tipo' => 'Nota de Crédito',   'codigo' => '05', 'token' => '7003'],
            ['tipo' => 'Sujeto Excluido',   'codigo' => '14', 'token' => '7005'],

        ];

        foreach ($tipoComprobantes as $tipo)
            tipo_comprobantes::firstOrCreate($tipo);
    }
}
