@extends('layouts.form')

@section('form')
<x-identificaciones-form table="{{ $th['table'] }}" :data="$data ??''" />
@endsection
