@extends('layouts.form')

@section('form')
    <x-ordenes-form table="{{ $th['table'] }}" :data="$data"/>
@endsection
