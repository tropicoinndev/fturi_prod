@extends('layouts.form')

@section('form')
    <x-administrar_habitaciones-form table="{{ $th['table'] }}"  :data="$data"/>
@endsection