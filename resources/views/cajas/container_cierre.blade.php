@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card" style="min-height: 90vh;">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12 text-uppercase h3">
                                Panel de impresion
                            </div>
                            <div class="col-12">
                                <!--a class="btn btn-outline-primary" href="{{ $url }}" target="_blank"
                                                        role="button">Vista PDF</a-->
                                <a class="btn btn-outline-secondary" href="{{ url()->previous() }}"
                                    role="button">Volver</a>
                                <a class="btn btn-outline-secondary" onclick="printIframeContent()"
                                    role="button">Imprimir</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <iframe src="{{ $url }}" frameborder="0" style="height: 55em; width: 100%;"
                                    id="printReport"></iframe>

                                <script>
                                    function printIframeContent() {
                                        const iframe = document.getElementById('printReport');
                                        if (iframe && iframe.contentWindow) {
                                            iframe.contentWindow.focus();
                                            iframe.contentWindow.print();
                                        } else {
                                            console.log("No se pudo encontrar el contenido del iframe.");
                                        }
                                    }
                                </script>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
