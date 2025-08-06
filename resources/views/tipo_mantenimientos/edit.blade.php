@extends('layouts.form')

@section('form')
    <x-tipo_mantenimientos-form table="{{ $th['table'] }}" :p="$p" :data="$data" />
@endsection