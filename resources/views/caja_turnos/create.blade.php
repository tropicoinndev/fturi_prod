@extends('layouts.form')

@section('form')
    <x-caja_turnos-form table="{{ $th['table'] }}" :data="$data"/>
@endsection
