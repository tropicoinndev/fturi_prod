@extends('layouts.form')

@section('form')
    <x-tipo_cortesia-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
