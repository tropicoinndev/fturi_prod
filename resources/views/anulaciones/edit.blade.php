@extends('layouts.form')

@section('form')
    <x-anulaciones-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
