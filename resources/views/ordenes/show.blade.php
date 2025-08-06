@extends('layouts.form')

@section('form')
    <x-ordenes-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
