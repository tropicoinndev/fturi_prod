@extends('layouts.form')

@section('form')
    <x-opcion_turnos-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
