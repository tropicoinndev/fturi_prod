@extends('layouts.form')

@section('form')
    <x-users-form table="{{ $th['table'] }}" :p="$p"  />
@endsection