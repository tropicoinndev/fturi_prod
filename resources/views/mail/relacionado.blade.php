@if (
    $json->documentoRelacionado != null &&
        count($json->documentoRelacionado) > 0 &&
        $json->documentoRelacionado[0]->tipoDocumento != null &&
        $json->documentoRelacionado[0]->tipoGeneracion != null &&
        $json->documentoRelacionado[0]->numeroDocumento != null &&
        $json->documentoRelacionado[0]->fechaEmision != null)

    @inject('utils', 'App\Utils')

    <div class="row">
        <div style="margin-top: 0.4cm; margin-bottom: 0.03cm;" class="col-12 text-center">
            <b class="fs-10pt">Documento relacionado</b>
        </div>
    </div>

    <div class="row ml-02 mr-02 relacionado">
        <div style="height: 1.7cm;">
            <div class="col-3">
                <p><b>Tipo de generación:</b></p>
                <p>{{ $utils->tipoGeneracion($json->documentoRelacionado[0]->tipoGeneracion) }}</p>
            </div>
    
            <div class="col-3">
                <p><b>Tipo de documento:</b></p>
                <p>{{ $utils->tipoDocumento($json->documentoRelacionado[0]->tipoDocumento) }}</p>
            </div>
    
            <div class="col-3">
                <p><b>Fecha de emisión:</b></p>
                <p>{{ $json->documentoRelacionado[0]->fechaEmision }}</p>
            </div>
    
            <div class="col-3">
                <p><b>Nº Documento:</b></p>
                <p>{{ $json->documentoRelacionado[0]->numeroDocumento }}</p>
            </div>
        </div>
    </div>
@endif
