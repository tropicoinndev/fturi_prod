@extends('layouts.form')

@section('form')
    <x-personas_alertas-form table="{{ $th['table'] }}" :data="$data"/>
@endsection
