@extends('layouts.form')

@section('form')
<x-anticipos-form table="{{ $th['table'] }}" :forma="$forma_pagos" />

@endsection
