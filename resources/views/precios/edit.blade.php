@extends('layouts.form')

@section('form')
    <x-precios-form table="{{ $th['table'] }}" :p="$p" :data="$data" />
@endsection