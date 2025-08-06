@if(isset($table))
    <div class="accordion" id="accordionExample">
        @if(request()->routeIs($table.'.index')){{--El primer acordión no se mostrará en la vista edit, solo el segundo--}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        --- IMPORTAR REGISTROS DESDE ARCHIVOS ---
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        {{--Form 1--}}
                        <form action="{{ route('personas_alertas.importData') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3 text-secondary">
                                *No se permite cambiar los nombres de las columnas a los documentos.
                                <br>
                                *No se permite agregar columnas adicionales a los documentos.
                            </div>

                            <div class="mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center mb-3">
                                            <div class="col-8">
                                                <p class="mb-1">Excel o CSV</p>
                                                <input class="form-control" type="file" name="personas_alertas_import" accept=".xlsx,.csv">
                                            </div>
                                            <div class="col-4 text-end">
                                                <button type="submit" class="btn btn-outline-primary mt-4"><span class="mdi mdi-send-variant-outline"></span> Enviar archivo</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('personas_alertas.descargarFormato',['format'=>Crypt::encryptString(1)]) }}">Descargar formato en Excel</a>
                            <br>
                            <a href="{{ route('personas_alertas.descargarFormato',['format'=>Crypt::encryptString(2)]) }}">Descargar formato en CSV</a>
                        </form>
                    </div>
                </div>
            </div> 
        @endif
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    --- CREAR REGISTROS DESDE CERO ---
                </button>
            </h2>

            {{--A éste acordión se aplica la clase 'show' solo si estamos en la vista edit--}}
            <div id="collapseTwo" class="accordion-collapse collapse @if(request()->routeIs($table.'.edit')) show @endif" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    {{--Form 2--}}
                    <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post" id="appPersonasAlertas">
                        @csrf

                        @if(isset($p) && $p->id > 0)
                            <input type="hidden" name="id" value="{{ $p->cid }}">
                        @endif

                        <div v-if="arrayPersonasAlertas.length > 0" class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <strong>***ESTÁ INTENTANDO AGREGAR UNA PERSONA RELACIONADA A ILICITOS**</strong>

                            <div v-for="pa in arrayPersonasAlertas" class="mt-2">
                                <p class="mb-0">Nombre: @{{ pa.nombres }} @{{ pa.apellidos }}</p>
                                <p class="mb-0">Alias: @{{ pa.alias ?? '---' }}</p>
                                <p class="mb-0">Nº de identificación: @{{ pa.numero_identificacion ?? '---' }}</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="mb-3">
                            <label for="nombres" class="form-label">Nombres:</label>
                            <input type="text" class="form-control @error('nombres') is-invalid @enderror" id="nombres" name="nombres" value="{{ old('nombres',$p->nombres ?? '') }}" autocomplete="off" placeholder="Escriba aquí..." aria-describedby="nombresHelp">
                            
                            @error('nombres')
                                <div id="nombresHelp" class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" class="form-control @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos',$p->apellidos ?? '') }}" autocomplete="off" placeholder="Escriba aquí..." aria-describedby="apellidosHelp">

                            @error('apellidos')
                                <div id="apellidosHelp" class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alias" class="form-label">Alias:</label>
                            <input type="text" class="form-control @error('alias') is-invalid @enderror" id="alias" name="alias" value="{{ old('alias',$p->alias ?? '') }}" autocomplete="off" placeholder="Escriba aquí..." aria-describedby="aliasHelp">

                            @error('alias')
                                <div id="aliasHelp" class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="numero_identificacion" class="form-label">Nº de identificación:</label>
                            <input type="text" class="form-control" id="numero_identificacion" name="numero_identificacion" value="{{ old('numero_identificacion',$p->numero_identificacion ?? '') }}" autocomplete="off" placeholder="Escriba aquí..." aria-describedby="numero_identificacionHelp">
                        </div>
                        
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Vue--}}
    <script type="module">
        let app = window.appVue({
            data(){
                return {
                    nombres: '',
                    arrayPersonasAlertas: [],
                }
            },
            mounted(){
                //console.log('Personas Alertas Mounted.');
            },
            methods: {
                apiNombres(){
                    axios.get(`/personas/alertas/nombre/api/${ (this.nombres).toUpperCase() }`)
                    .then((r) => {
                        if(!r.data.error){
                            this.arrayPersonasAlertas = r.data.list;
                        }
                        console.log('API Nombres: ',r);
                    }).catch((e) => {
                        console.log('Error JS: ',e);
                    });
                },
            },
            computed: {
                //Code...
            },
            watch: {
                //Code...
            },
        });
        app.mount('#appPersonasAlertas');
    </script>
@else
    Este formulario requiere lo atributos :table
@endif
