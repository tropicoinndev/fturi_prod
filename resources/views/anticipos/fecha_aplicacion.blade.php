@extends('layouts.anticipos')

@section('panel_anticipo')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-12 col-lg-6">
                        <h3 class="card-title text-capitalize">
                            Cambiar fecha de aplicación {{ $p->id }}
                        </h3>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('anticipos.cambiarAplicacionStore') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $p->cid }}" required>
                            <div class="mb-3">
                                <label for="" class="form-label">Fecha de aplicación</label>
                                <input type="date" class="form-control" name="fecha_aplicacion" id=""
                                    aria-describedby="helpId" placeholder="" required />
                                <small id="helpId" class="form-text text-muted">Fecha actual de aplicacion:
                                    {{ $p->fecha_aplicacion }}</small>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="confirm"
                                        id="confirm" required>
                                    <label class="form-check-label" for="confirm">
                                        Confirmo que quiero cambiar la fecha de aplicación del anticipo Nº
                                        {{ $p->id }}
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary " type="submit">Cambiar fecha</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
