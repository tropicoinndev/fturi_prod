@extends('layouts.form')

@section('form')
    <x-categorias_precios-form table="{{ $th['table'] }}" :p="$p" :data="$data" />
@endsection