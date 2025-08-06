@extends('layouts.form')

@section('form')
    <x-clientes_contactos-form table="{{ $th['table'] }}" :data="$data"/>
@endsection
