@extends('layouts.form')

@section('form')

<x-tarifa :tarifa="\Crypt::encryptString($p->id)"
    :habitaciones="$habitaciones"
    :tarifas="$tarifas"
/>

@endsection
