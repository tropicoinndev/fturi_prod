@extends('layouts.form')

@section('form')
    <x-tarifas-form table="{{ $th['table'] }}" :p="$p" :data="$data" />
@endsection