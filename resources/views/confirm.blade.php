@extends('layouts.form')
@section('form')
    <form action="{{ route($route ?? $th['table'] . '.delete') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{ \Crypt::encryptString($p->id) }}">
        <div class="row">
            <div class="col-12 h4 mt-2">
                <span class="mdi mdi-alert text-danger"></span> ¿Esta seguro de borrar este registro?
            </div>
            <div class="col-12 mt-1">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="confirm" id="" value="1"
                            required>
                        Si, quiero borrar este registro
                    </label>
                </div>
            </div>
            <div class="col-12 mt-5">
                <button class="btn btn-danger" type="submit"><span class="mdi mdi-delete"></span> Borrar</button>
                <a class="btn btn-light" href="{{ URL::previous() }}">
                    <span class=" mdi mdi-back"></span>
                    Volver
                </a>
            </div>
        </div>
    </form>
@endsection
