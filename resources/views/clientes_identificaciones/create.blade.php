@extends('layouts.form')

@section('form')
    <x-clientes_identificaciones-form table="{{ $th['table'] }}" :data="$data"/>
@endsection
