@extends('layouts.form')

@section('form')
    <x-correlativos-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
