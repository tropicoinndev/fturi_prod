@extends('layouts.form')

@section('form')
    <x-turnos-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection