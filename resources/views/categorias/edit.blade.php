@extends('layouts.form')

@section('form')
    <x-categorias-form table="{{ $th['table'] }}" :p="$p" />
@endsection