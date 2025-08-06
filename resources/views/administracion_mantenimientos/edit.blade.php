@extends('layouts.form')

@section('form')
    <x-administracion_mantenimientos-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection