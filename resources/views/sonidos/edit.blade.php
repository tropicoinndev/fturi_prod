@extends('layouts.form')

@section('form')
    <x-sonidos-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
