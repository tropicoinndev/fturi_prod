@extends('layouts.form')

@section('form')
    <x-tarifas-form table="{{ $th['table'] }}" :data="$data"/>
@endsection