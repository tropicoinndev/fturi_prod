@extends('layouts.form')

@section('form')
    <x-caja_turnos-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
