@extends('layouts.form')

@section('form')
    <x-tipo_habitaciones-form table="{{ $th['table'] }}"/>
@endsection
