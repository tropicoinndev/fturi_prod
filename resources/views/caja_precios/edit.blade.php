@extends('layouts.form')

@section('form')
    <x-caja_precios-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection