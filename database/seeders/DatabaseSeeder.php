<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PaisesSeeder::class);
        $this->call(DepartamentosSeeder::class);
        $this->call(MunicipiosSeeder::class);
        $this->call(ActividadesEconomicasSeeder::class);
        $this->call(GirosSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(ContingenciasSeeder::class);
        $this->call(TipoComprobantesSeeder::class);
        $this->call(IdentificacionesSeeder::class);
    }
}
