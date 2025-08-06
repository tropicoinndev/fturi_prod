@extends('layouts.form')

@section('form')
    <x-clientes-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
