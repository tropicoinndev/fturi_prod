@extends('layouts.form')

@section('form')
    <x-caja_precios-form table="{{ $th['table'] }}" :data="$data"/>
@endsection
