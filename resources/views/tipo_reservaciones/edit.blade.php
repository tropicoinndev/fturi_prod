@extends('layouts.form')

@section('form')
    <x-tipo_reservaciones-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
