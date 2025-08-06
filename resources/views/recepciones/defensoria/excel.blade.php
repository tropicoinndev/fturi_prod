<table>
    <thead>
        <tr>
            <th scope="col">Dia</th>
            <th scope="col">Fecha</th>
            <th scope="col">Habitaciones disponibles</th>
            <th scope="col">Habitaciones ocupadas</th>
        </tr>
    </thead>
    <tbody>
        @php
            use Carbon\Carbon;
            $ahora = Carbon::now();
            $mes = $ahora->month - 1;
            #$mes = $ahora->month;
            $anio = $ahora->year;
            $diasDelMes = Carbon::create($anio, $mes, 1)->daysInMonth;

            $contadorFechas = 0;
        @endphp

        @foreach (range(1, $diasDelMes) as $dia)
            @php
                $fecha = Carbon::create($anio, $mes, $dia);

                $recepciones = \App\Models\recepciones::whereDate('fecha_ingreso', '<=', $fecha->format('Y-m-d'))
                    ->whereDate('fecha_salida', '>=', $fecha->format('Y-m-d'))
                    ->whereHas('habitaciones', function ($q) use ($sucursalId) {
                        $q->where('sucursales_id', $sucursalId)->where('glorieta', false);
                    })
                    #->where('eliminado',false)#Tropico Inn: false, Tropiclub: todas (tomar las facturadas y anuladas tambien)
                    ->when($sucursalId == 1, function ($q) {
                        $q->where('eliminado', false);
                    })
                    ->get();

                $contadorFechas = 0;
                foreach ($recepciones as $recep) {
                    #Contar tambien las fechas si la sucursal es Tropiclub para que tome las facturadas y las anuladas
                    if ($fecha->format('Y-m-d') < $recep->fecha_salida || $sucursalId == 2) {
                        $contadorFechas++;
                    }
                }
            @endphp

            <tr>
                <th>{{ $dia }}</th>
                <td>{{ $fecha->format('d/m/Y') }}</td>
                <td></td>
                <td>{{ $contadorFechas }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
