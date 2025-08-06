@extends('layouts.form')

@section('form')
    <x-administrar_habitaciones-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
