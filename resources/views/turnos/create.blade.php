@extends('layouts.form')

@section('form')
    <x-turnos-form table="{{ $th['table'] }}" :data="$data"/>
@endsection