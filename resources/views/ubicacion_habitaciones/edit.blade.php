@extends('layouts.form')

@section('form')
    <x-ubicacion_habitaciones-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
