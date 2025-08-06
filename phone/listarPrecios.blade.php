@extends('layouts.app')

@section('style')
    <style>
        body {
            background-color: #E0F2F1;
            font-family: sans-serif;
        }
        .menuSinScroll {/*Por defecto, su posicion es fijo en la parte superior*/
            background: #37474F;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            height: 110px;
        }
        .menuConScroll {
            position: fixed;
            top: 0;
            background: #37474F;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            height: 65px;
        }
        .ocultarAlHacerScroll {
            visibility: hidden;
        }
        .title {/*Titulo del header de cada pantalla*/
            color: #FAFAFA;
            font-size: 13px;
            font-weight: bold;
            font-family: sans-serif;
            letter-spacing: 0.5px;
        }
        .subtitle {/*Subtitulo del header de cada pantalla*/
            color: #FAFAFA;
            font-size: 10px;
            font-family: sans-serif;
            letter-spacing: 0.5px;
        }
        .bi-arrow-left, .bi-x-lg, .bi-search {/*Iconos del header de flecha izquierda y equis*/
            color: #FAFAFA;
            font-size: 20px;
        }
        .panel-main {/*Este es el panel de color blanco, sobre él se colocaran las card celestes*/
            border-radius: 20px;
            background: #FAFAFA;
            padding: 10px;
            width: 93% !important;
            margin-top: -30px !important;
        }
        .panel-card {/*Estas son las card de color celeste donde se muestra la información*/
            background: #E3F2FD;
            border-radius: 8px;
            border-bottom: solid 4px #BBDEFB;
            padding: 10px;
        }
        .abecedario {
            background: #DAE0E5;
            color: #546E7A;
            border-radius: 100%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            padding: 1px;
            margin-right: 3px;
            /*text-align: center;*/
        }
        .letters {/*Se usa unicamente para colocar/centrar las letras dentro del boton*/
            margin-left: -1px;
        }
        /*Configuraciones de la modal*/
        .modal-content {
            background: #37474F;
            position: fixed;/*Indica la posicion inferior a la que se colocara la modal*/
            bottom: 0;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
        }
        .ocultarElementosModal {
            display: none;
        }
        @media(max-width: 767px){/*Se cambiara el tamaño de la modal y se ocultara el div que contiene el abecedario cuando la resolucion sea un telefono*/
            #abecedarioDiv {
                display: none;
            }
            .modal-content {
                left: 0;    /*Agregamos esta línea para asegurar que la modal ocupe todo el ancho de la pantalla*/
                width: 100%;/*Establecemos el ancho al 100% para dispositivos móviles*/
            }
        }
        @media(min-width: 768px) and (max-width: 991px){/*Se cambiara el tamaño de la modal y se ocultara el div que contiene el abecesario cuando la resolucion sea una tablet*/
            #abecedarioDiv {
                display: block;
            }
            .modal-content {
                margin: 0 auto;/*Centramos la modal en dispositivos tablet*/
                width: 500px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center" style="margin-top: -24px;">
            

            <!-- panel header -->
            <div class="menuSinScroll" id="menu">
                <div class="container-fluid mt-3">
                    <div class="row">
                        <div class="col-1">
                            <a href="{{ redirect()->back() }}" style="text-decoration: none;">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                        <div class="col-9">
                            <h1 style="margin-top: 8px;" class="title ms-2">PRECIOS</h1>
                            <div id="titles">
                                <h2 style="margin-top: -3px;" class="subtitle ms-2 mb-3">Hola, ADMIN</h2>
                                {{-- <h1 class="title ms-2">CLIENTE / TITULAR ASIGNADO</h1> --}}
                            </div>
                        </div>
                        <div class="col-2 text-end">
                            <i id="iconClose" class="bi bi-x-lg me-1"></i>
                            <i id="iconSearch" class="bi bi-search me-1"></i>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <input id="txtSearch" type="text" class="form-control rounded-pill" placeholder="Buscar por detalle">
                        </div>
                    </div>
                </div>
            </div><!-- end panel header-->


            <!-- panel main -->
            <div class="panel-main">
                <div class="row">
                    <div class="col-12">
                        <div id="abecedarioDiv" class="mb-2"><!--Este div se ocultara cuando el tamaño sea movil, y se mostrara cuando el tamaño sea tablet-->
                            <p style="color: #37474F; font-size: 10px; margin-top: 12px; margin-bottom: 10px;" class="ms-2">FILTRAR POR</p>
                            <div style="margin-top: -5px;" class="text-center">
                                <button class="btn abecedario"><span class="letters">A</span></button>
                                <button class="btn abecedario"><span class="letters">B</span></button>
                                <button class="btn abecedario"><span class="letters">C</span></button>
                                <button class="btn abecedario"><span class="letters">D</span></button>
                                <button class="btn abecedario"><span class="letters">E</span></button>
                                <button class="btn abecedario"><span class="letters">F</span></button>
                                <button class="btn abecedario"><span class="letters">G</span></button>
                                <button class="btn abecedario"><span class="letters">H</span></button>
                                <button class="btn abecedario"><span class="letters">I</span></button>
                                <button class="btn abecedario"><span class="letters">J</span></button>
                                <button class="btn abecedario"><span class="letters">K</span></button>
                                <button class="btn abecedario"><span class="letters">L</span></button>
                                <button class="btn abecedario"><span class="letters">M</span></button>
                                <button class="btn abecedario"><span class="letters">N</span></button>
                                <button class="btn abecedario"><span class="letters">Ñ</span></button>
                                <button class="btn abecedario"><span class="letters">O</span></button>
                                <button class="btn abecedario"><span class="letters">P</span></button>
                                <button class="btn abecedario"><span class="letters">Q</span></button>
                                <button class="btn abecedario"><span class="letters">R</span></button>
                                <button class="btn abecedario"><span class="letters">S</span></button>
                                <button class="btn abecedario"><span class="letters">T</span></button>
                                <button class="btn abecedario"><span class="letters">U</span></button>
                                <button class="btn abecedario"><span class="letters">V</span></button>
                                <button class="btn abecedario"><span class="letters">W</span></button>
                                <button class="btn abecedario"><span class="letters">X</span></button>
                                <button class="btn abecedario"><span class="letters">Y</span></button>
                                <button class="btn abecedario"><span class="letters">Z</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <p style="color: #37474F; font-size: 12px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;">SELECCIONE UN PRECIO</p>

                <div class="row mt-1">
                    @php
                        $comandas = [
                            ['producto'=>'CASILLERO DEL DIABLO', 'categoria'=>'BOTELLA DE VINO',     'precio'=>35.62],
                            ['producto'=>'PIÑA COLADA',          'categoria'=>'COCTELES',            'precio'=>5.25],
                            ['producto'=>'CERVEZA GOLDEN',       'categoria'=>'CERVEZA NACIONAL',    'precio'=>1.40],
                            ['producto'=>'AGUA MINERAL',         'categoria'=>'BEBIDAS SIN ALCOHOL', 'precio'=>1.50],
                            ['producto'=>'CASILLERO DEL DIABLO', 'categoria'=>'BOTELLA DE VINO',     'precio'=>35.62],
                            ['producto'=>'PIÑA COLADA',          'categoria'=>'COCTELES',            'precio'=>5.25],
                            ['producto'=>'CERVEZA GOLDEN',       'categoria'=>'CERVEZA NACIONAL',    'precio'=>1.40],
                            ['producto'=>'AGUA MINERAL',         'categoria'=>'BEBIDAS SIN ALCOHOL', 'precio'=>1.50],
                            ['producto'=>'CASILLERO DEL DIABLO', 'categoria'=>'BOTELLA DE VINO',     'precio'=>35.62],
                            ['producto'=>'PIÑA COLADA',          'categoria'=>'COCTELES',            'precio'=>5.25],
                            ['producto'=>'CERVEZA GOLDEN',       'categoria'=>'CERVEZA NACIONAL',    'precio'=>1.40],
                            ['producto'=>'AGUA MINERAL',         'categoria'=>'BEBIDAS SIN ALCOHOL', 'precio'=>1.50],
                            ['producto'=>'CASILLERO DEL DIABLO', 'categoria'=>'BOTELLA DE VINO',     'precio'=>35.62],
                            ['producto'=>'PIÑA COLADA',          'categoria'=>'COCTELES',            'precio'=>5.25],
                            ['producto'=>'CERVEZA GOLDEN',       'categoria'=>'CERVEZA NACIONAL',    'precio'=>1.40]
                        ];
                    @endphp

                    @foreach($comandas as $c)
                        <div class="col-12 mb-2">
                            <div class="panel-card">
                                <div class="row" data-bs-toggle="modal" data-bs-target="#modalAgregarCantidad">
                                    <div style="margin-top: -4px;" class="col-9">
                                        <b style="font-size: 12px; color: #37474F;" class="text-truncate ms-3">{{ $c['producto'] }}</b>
                                        <p style="color: #546E7A; font-size: 10px; margin-top: -1px; margin-bottom: -2px;" class="ms-3">{{ $c['categoria'] }}</p>
                                    </div>
                                    <div style="margin-top: -4px;" class="col-3 text-end">
                                        <b style="color: #37474F; font-size: 13px;" class="me-2">$ {{ $c['precio'] }}</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div><!-- end panel main -->



            <!-- Modal agregar cantidad -->
            <div class="modal fade" id="modalAgregarCantidad" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="position-relative mb-4"><!--Div relativo para posicionar los iconos a la izquierda y derecha-->
                                <div class="position-absolute top-0 start-0" style="margin-top: 4px;">
                                    <h1 style="color: #FAFAFA;" class="modal-title fs-6 ms-3" id="staticBackdropLabel">PIÑA COLADA</h1>
                                </div>
                
                                <!--Icono derecho-->
                                <div class="position-absolute top-0 end-0" style="margin-top: 4px;">
                                    <span data-bs-dismiss="modal" aria-label="Close">
                                        <i style="color: #FAFAFA; font-size: 20px;" class="bi bi-x-lg me-3"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col px-0 py-3">
                                        <div style="height: 210px; margin-top: 10px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card p-3">
                                            <p style="color: #546E7A; font-size: 11px; margin-bottom: 2px;">SALIDA DE BLACK AND WHITE</p>
                                            <p style="color: #37474F; font-size: 11px; margin-bottom: 5px; font-weight: bold;">$ 1.75</p>

                                            <div id="ocultarCantidad">
                                                <p style="color: #546E7A; margin-bottom: 2px;">Cantidad</p>
                                                <input style="border-color: #26C6DA;" type="number" class="form-control mb-2" placeholder="1">
                                            </div>
                                            <div id="ocultarObservaciones">
                                                <p style="color: #546E7A; margin-bottom: 2px;">Observaciones</p>
                                                <textarea style="border-radius: 6px; border-color: #787878;" class="form-control form-control-sm mb-2" cols="30" rows="2" placeholder="Agregue las observaciones necesarias para este producto en este campo. (Max. 200 caracteres)"></textarea>
                                            </div>
        
                                            <div class="row align-items-center">
                                                <div class="col-6 mt-3">
                                                    <p id="lblAgregarObservacion" style="color: #546E7A; font-size: 11px;"><i class="bi bi-check2-all"></i> Agregar observacion</p>
                                                    <p id="lblAgregar" style="color: #546E7A; font-size: 11px; margin-top: -12px;">Agregar</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <button id="btnAgregar" style="background: #DAE0E5; color: #546E7A;" type="button" class="btn"><i class="bi bi-plus-lg"></i> Agregar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div><!--End modal agregar cantidad-->


        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            //Declaracion de variables
            let menu              = document.getElementById("menu");
            let iconSearch        = document.getElementById('iconSearch');
            let iconClose         = document.getElementById('iconClose');
            let titles            = document.getElementById('titles');
            let txtSearch         = document.getElementById('txtSearch');

            let statusBtnSearch = false;
            let statusBtnClose  = false;
            let firstLoad       = true;

            let menuHeightWithoutScroll = `${140}px`;//Medidas en px
            let menuHeightWithScroll    = `${65}px`; //Medidas en px
            let maxHeightToScroll       = 65;        //Altura a la que se comanzara a hacer scroll

            //Ocultar y mostrar elementos en la modal
            let ocultarCantidad = document.getElementById('ocultarCantidad');
            let ocultarObservaciones = document.getElementById('ocultarObservaciones');
            ocultarObservaciones.classList.add('ocultarElementosModal');

            let lblAgregarObservacion = document.getElementById('lblAgregarObservacion');
            let lblAgregar = document.getElementById('lblAgregar');

            lblAgregar.classList.add('ocultarElementosModal');
            let btnAgregar = document.getElementById('btnAgregar');

            //Iniciar por defecto con algunos elementos ocultos
            iconClose.style.display = 'none';
            txtSearch.style.display = 'none';
            
            //Escucha de botones
            iconSearch.addEventListener('click',function(){
                //console.log('Click icon search');
                statusBtnSearch = true;

                if(window.scrollY > maxHeightToScroll){
                    clickBtnSearchWithScroll();
                }
                else{
                    clickBtnSearchWithoutScroll();
                }
            });

            iconClose.addEventListener('click',function(){
                //console.log('Click icon close');
                statusBtnClose = true;

                if(window.scrollY > maxHeightToScroll){
                    clickBtnCloseWithScroll();
                }
                else{
                    clickBtnCloseWithoutScroll();
                }
            });

            //Escucha de botones de la modal
            lblAgregarObservacion.addEventListener('click',function(){
                ocultarCantidad.classList.add("ocultarElementosModal");
                ocultarObservaciones.classList.remove('ocultarElementosModal');
                lblAgregarObservacion.classList.add('ocultarElementosModal');
                lblAgregar.classList.remove('ocultarElementosModal');
                btnAgregar.classList.add('ocultarElementosModal');
            });
            lblAgregar.addEventListener('click',function(){
                ocultarCantidad.classList.remove("ocultarElementosModal");
                ocultarObservaciones.classList.add('ocultarElementosModal');
                lblAgregarObservacion.classList.remove('ocultarElementosModal');
                lblAgregar.classList.add('ocultarElementosModal');
                btnAgregar.classList.remove('ocultarElementosModal');
            });

            //Funciones que se usan para mostrar y ocultrar elementos cuando no se ha hecho scroll
            function clickBtnSearchWithoutScroll(){
                menu.style.height        = menuHeightWithoutScroll;//Aumentamos la altura del menu
                titles.style.display     = 'none';
                iconSearch.style.display = 'none';
                iconClose.style.display  = 'block';
                txtSearch.style.display  = 'block';
            }
            function clickBtnCloseWithoutScroll(){
                menu.style.height        = '110px';//Disminuimos la altura del menu
                titles.style.display     = 'block';
                iconSearch.style.display = 'block';
                iconClose.style.display  = 'none';
                txtSearch.style.display  = 'none';
            }

            //funciones que se usan para mostrar y ocultar elementos cuando ya se hizo scroll
            function clickBtnSearchWithScroll(){
                clickBtnSearchWithoutScroll();
            }
            function clickBtnCloseWithScroll(){
                menu.style.height        = menuHeightWithScroll;//Disminuimos la altura del menu
                titles.style.display     = 'none';
                iconSearch.style.display = 'block';
                iconClose.style.display  = 'none';
                txtSearch.style.display  = 'none';
            }

            function scroll(){
                window.addEventListener("scroll",function(){
                    //console.log("Scroll", window.scrollY);
                    
                    if(window.scrollY > maxHeightToScroll){//Con scroll
                        menu.classList.add("menuConScroll");
                        menu.classList.remove("menuSinScroll");

                        if(firstLoad){
                            titles.style.display = 'none';
                        }

                        if(statusBtnSearch){
                            menu.style.height    = menuHeightWithoutScroll;//Aumentamos la altura del menu
                            titles.style.display = 'none';
                            //statusBtnSearch = false;
                            statusBtnClose  = false;
                        }

                        if(statusBtnClose){
                            menu.style.height    = menuHeightWithScroll;//Disminuimos la altura del menu
                            titles.style.display = 'none';
                        }
                        //titles.style.display = 'none';
                    }
                    else{//Sin scroll
                        menu.classList.add("menuSinScroll");
                        menu.classList.remove("menuConScroll");

                        if(firstLoad){
                            titles.style.display = 'block';
                        }
                        
                        if(statusBtnSearch){
                            menu.style.height    = menuHeightWithoutScroll;//Aumentamos la altura del menu
                            titles.style.display = 'none';
                            statusBtnSearch = false;
                            statusBtnClose  = false;
                            firstLoad       = false;
                        }

                        if(statusBtnClose){
                            menu.style.height    = '110px';//Aumentamos la altura del menu
                            titles.style.display = 'block';
                        }
                        //titles.style.display = 'block';
                    }
                });
            }
            scroll();
        });
    </script>
@endsection
