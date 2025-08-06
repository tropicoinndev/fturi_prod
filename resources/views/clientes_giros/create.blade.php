@extends('layouts.form')

@section('form')
    <x-clientes_giros-form table="{{ $th['table'] }}" :data="$data"/>
@endsection
