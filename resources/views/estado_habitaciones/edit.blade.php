@extends('layouts.form')

@section('form')
    <x-estado_habitaciones-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
