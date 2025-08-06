@extends('layouts.form')

@section('form')
    <x-giros-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
