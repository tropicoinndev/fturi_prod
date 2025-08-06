@extends('layouts.form')

@section('form')
    <x-forma_habitaciones-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
