@extends('layouts.form')

@section('form')
<x-departamentos-form table="{{ $th['table'] }}"  :data="$data" />
@endsection
