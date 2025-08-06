@extends('layouts.form')

@section('form')
    <x-cortesias-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
