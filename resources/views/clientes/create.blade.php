@extends('layouts.form')

@section('form')
    <x-clientes-form table="{{ $th['table'] }}" :data="$data"/>
@endsection
