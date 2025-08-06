@extends('layouts.form')

@section('form')

<x-tipo_mantenimiento :mantenimiento="\Crypt::encryptString($p->id)"
    :tipoMantenimientoU="$tipoMantenimientoU" :usuarios="$usuarios" :tipomantenimientos="$tipomantenimientos"
    
/>

@endsection