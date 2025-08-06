@extends('layouts.form')

@section('form')
    <x-roles-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
