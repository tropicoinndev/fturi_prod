@extends('layouts.form')

@section('form')
    <x-servicios-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
