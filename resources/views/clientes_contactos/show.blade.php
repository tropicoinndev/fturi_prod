@extends('layouts.form')

@section('form')
    <x-clientes_contactos-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
