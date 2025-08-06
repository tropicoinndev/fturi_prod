@extends('layouts.form')

@section('form')
    <x-tarifa_detalles :tarifa="\Crypt::encryptString($p->id)" :formaHabitaciones="$formaHabitaciones" :tipoHabitaciones="$tipoHabitaciones" :tarifaDetalles="$tarifaDetalles" :tarifas="$p"
        :tarifasAll="$tarifas" :habitaciones="$habitaciones">
    </x-tarifa_detalles>
@endsection
