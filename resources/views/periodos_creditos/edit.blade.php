@extends('layouts.form')

@section('form')
    <x-periodos_creditos-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
