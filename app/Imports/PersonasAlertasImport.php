<?php

namespace App\Imports;

use App\Models\personas_alertas;
use Maatwebsite\Excel\Concerns\ToModel;

#Add
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class PersonasAlertasImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    public function getCsvSettings(): array {
        return [
            'delimiter'=>';',#Delimitador personalizado para archivos CSV
        ];
    }

    public function model(array $row){
        #return new personas_alertas([
        return personas_alertas::firstOrCreate(
            #Condiciones para verificación de existencias
            [
                'numero_identificacion'=>$row['numero_identificacion'],
            ],

            #Array de datos a crear
            [
            'nombres'  =>$row['nombres'] ?? null,
            'apellidos'=>$row['apellidos'] ?? null,
            'alias'    =>$row['alias'] ?? null,
            'numero_identificacion'=>$row['numero_identificacion'] ?? null,
            'ilicita'  =>$row['persona_buscada'] ?? false,
            'peps'     =>$row['peps'] ?? false,
            'users_id' =>auth()->id(),
        ]);
    }
}
