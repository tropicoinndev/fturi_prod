@extends('layouts.form')

@section('form')
    <x-salones-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
