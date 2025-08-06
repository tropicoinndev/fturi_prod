@extends('layouts.form')

@section('form')
    <x-identificaciones-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
