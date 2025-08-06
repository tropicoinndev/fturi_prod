@extends('layouts.form')

@section('form')
    <x-tipo_registros-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
