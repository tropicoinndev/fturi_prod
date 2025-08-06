@extends('layouts.anticipos')

@section('panel_anticipo')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-12 col-lg-6">
                        <h3 class="card-title text-capitalize">
                            Cambiar forma de pago del anticipo #{{ $p->id }}
                        </h3>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('anticipos.cambiarFormaPagoStore') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $p->cid }}" required>
                            <div class="mb-3">
                                <label for="forma_pagos_id" class="form-label">Forma de pago:</label>
                                <select class="form-select" aria-label="Default select example" name="forma_pagos_id" id="forma_pagos_id">
                                    <option value="{{ Crypt::encryptString($p->forma_pagos_id) }}" selected>{{ $p->forma_pagos->forma }}</option>
                                    @foreach($formaPagos as $fp)
                                        <option value="{{ $fp->cid }}">{{ $fp->forma }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="confirm"
                                        id="confirm" required>
                                    <label class="form-check-label" for="confirm">
                                        Confirmo que quiero cambiar la forma de pago del anticipo Nº
                                        {{ $p->id }}
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary " type="submit">Cambiar forma de pago</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
