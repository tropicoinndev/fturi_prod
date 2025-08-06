@extends('layouts.form')

@section('form')
    <x-paises-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
