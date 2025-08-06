@extends('layouts.form')

@section('form')
    <x-contingencias-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
