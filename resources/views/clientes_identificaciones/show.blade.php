@extends('layouts.form')

@section('form')
    <x-clientes_identificaciones-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
