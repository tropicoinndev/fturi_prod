@extends('layouts.form')

@section('form')
    <x-rubros-form table="{{ $th['table'] }}" :p="$p" />
@endsection
