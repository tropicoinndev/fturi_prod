@extends('layouts.form')

@section('form')
    <x-personas_alertas-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
