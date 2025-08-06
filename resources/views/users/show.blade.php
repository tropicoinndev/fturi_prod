@extends('layouts.form')

@section('form')

<x-usuario :user="\Crypt::encryptString($p->id)" :roles="$roles" :cajasU="$cajasU" :rolesU="$rolesU" :tipoM="$tipoM"
    :mantenimientosUsuarios="$mantenimientosUsuarios" :usuarios="$usuarios"></x-usuario>

@endsection