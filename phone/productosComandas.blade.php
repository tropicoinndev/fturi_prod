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
            height: 140px;
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
        .panel-fixed {/*Este panel se usa para posicionar el botón azul en la parte inferior de la pantalla*/
            position: fixed;
            text-align: center;
            bottom: 25px;
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
        @media(max-width: 767px){/*Se cambiara el tamaño de la modal cuando la resolucion sea un telefono*/
            .navbar-expand-md {/*Ocultamos el menu superior cuando esta en tamaño movil*/
                display: none;
            }
            .modal-content {
                left: 0;    /*Agregamos esta línea para asegurar que la modal ocupe todo el ancho de la pantalla*/
                width: 100%;/*Establecemos el ancho al 100% para dispositivos móviles*/
            }
        }
        @media(min-width: 768px) and (max-width: 991px){/*Se cambiara el tamaño de la modal cuando la resolucion sea una tablet*/
            .navbar-expand-md {/*Ocultamos el menu superior cuando esta en tamaño movil*/
                display: none;
            }
            .modal-content {
                margin: 0 auto;/*Centramos la modal en dispositivos de tablet*/
                width: 500px;
            }
        }
        @media (min-width: 992px){
            .navbar-expand-md {/*Ocultamos el menu superior cuando esta en tamaño movil*/
                display: none;
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
                            <a href="{{ url()->previous() }}" style="text-decoration: none;">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                        <div class="col-9">
                            <h1 style="margin-top: 8px;" class="title ms-2">COMANDA # 25</h1>
                            <div id="titles">
                                <h2 style="margin-top: -3px;" class="subtitle ms-2 mb-3" data-bs-toggle="modal" data-bs-target="#modalCambiarBodega">ADMIN · BLACK AND WHITE</h2>
                                <h1 class="title ms-2">CLIENTE / TITULAR ASIGNADO</h1>
                            </div>
                        </div>
                        <div class="col-2 text-end">
                            <i id="iconClose" class="bi bi-x-lg me-1"></i>
                            <i id="iconSearch" class="bi bi-search me-1"></i>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <input id="txtSearch" type="text" class="form-control rounded-pill" placeholder="Buscar por nombre de producto">
                        </div>
                    </div>
                </div>
            </div><!-- end panel header-->


            <!-- panel main -->
            <div class="panel-main">
                <p style="color: #37474F; font-size: 12px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;">PRODUCTOS AGREGADOS</p>

                <div class="row mb-5">
                    @php
                        $comandas = [
                            ['cantidad'=>1,  'producto'=>'COÑAC',       'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>1.2],
                            ['cantidad'=>2,  'producto'=>'PIÑA COLADA', 'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>1.75],
                            ['cantidad'=>3,  'producto'=>'MARGARITA',   'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>5.25],
                            ['cantidad'=>4,  'producto'=>'COÑAC',       'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>2.50],
                            ['cantidad'=>5,  'producto'=>'PIÑA COLADA', 'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>2.50],
                            ['cantidad'=>6,  'producto'=>'MARGARITA',   'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>2.50],
                            ['cantidad'=>7,  'producto'=>'COÑAC',       'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>1.2],
                            ['cantidad'=>8,  'producto'=>'PIÑA COLADA', 'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>1.75],
                            ['cantidad'=>9,  'producto'=>'MARGARITA',   'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>5.25],
                            ['cantidad'=>10, 'producto'=>'COÑAC',       'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>1.2],
                            ['cantidad'=>11, 'producto'=>'PIÑA COLADA', 'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>1.75],
                            ['cantidad'=>12, 'producto'=>'MARGARITA',   'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>5.25],
                            ['cantidad'=>13, 'producto'=>'COÑAC',       'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>2.50],
                            ['cantidad'=>14, 'producto'=>'PIÑA COLADA', 'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>2.50],
                            ['cantidad'=>15, 'producto'=>'MARGARITA',   'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>2.50],
                            ['cantidad'=>16, 'producto'=>'COÑAC',       'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>1.2],
                            ['cantidad'=>17, 'producto'=>'PIÑA COLADA', 'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>1.75],
                            ['cantidad'=>18, 'producto'=>'MARGARITA',   'bodega'=>'SOLICITADO A BLACK AND WHITE', 'precio'=>5.25],
                        ];
                    @endphp

                    @foreach($comandas as $c)
                        <div class="col-12 mb-2">
                            <div class="panel-card">
                                <div class="row">
                                    <div style="margin-top: 5px;" class="col-1 text-center">
                                        <b style="color: #37474F; font-size: 11px; margin-left: 5px;">{{ $c['cantidad'] }}</b>
                                    </div>
                                    <div style="margin-top: -4px;" class="col-8">
                                        <b style="font-size: 12px; color: #37474F;" class="text-truncate">{{ $c['producto'] }}</b>
                                        <p style="color: #546E7A; font-size: 10px; margin-top: -1px; margin-bottom: -2px;">{{ $c['bodega'] }}</p>
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



            <!-- panel bottom -->
            <div class="panel-fixed">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="btn-group" role="group" aria-label="Basic example">

                            <button type="button" class="btn btn-primary btn-lg rounded-start-circle">
                                <a href="{{ route('categorias.index') }}" style="text-decoration: none;">
                                    <i style="color: white;" class="bi bi-list-ul"></i>
                                </a>
                            </button>

                            <button type="button" class="btn btn-primary btn-lg">
                                <i class="bi bi-cart"></i>
                            </button>

                            <span style="background-color: #0d6efd; padding: 3px;">
                                <button style="height: 50px; background-color: #FAFAFA;" type="button" class="btn btn-outline-primary btn-lg rounded-start-circle rounded-end-circle">
                                    <i class="bi bi-printer" style="color: #0d6efd;"></i>
                                </button>
                            </span>

                            <button type="button" class="btn btn-primary btn-lg">
                                <i class="bi bi-file-earmark-text"></i>
                            </button>

                            <button id="btnSearch" type="button" class="btn btn-primary btn-lg rounded-end-circle">
                                <i class="bi bi-search"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </div><!-- end panel bottom -->



            <!-- Modal cambiar bodega -->
            <div class="modal fade" id="modalCambiarBodega" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="position-relative mb-4"><!--Div relativo para posicionar los iconos a la izquierda y derecha-->
                                <div class="position-absolute top-0 start-0" style="margin-top: 4px;">
                                    <h1 style="color: #FAFAFA;" class="modal-title fs-6 ms-3" id="staticBackdropLabel">CAMBIAR BODEGA</h1>
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
                                        <div style="background: #fff; margin-top: 10px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card p-3">
                                            
                                            <div class="row mt-1 mb-2">
                                                @php
                                                    $bodegas = [
                                                        ['bodega'=>'RECEPCION'],
                                                        ['bodega'=>'BLACK AND WHITE'],
                                                        ['bodega'=>'RANCHO'],
                                                        ['bodega'=>'RESTAURANTE']
                                                    ];
                                                @endphp
                            
                                                @foreach($bodegas as $b)
                                                    <div class="col-12 mb-2">
                                                        <div class="panel-card">
                                                            <div class="row">
                                                                <div class="col-10">
                                                                    <b style="font-size: 12px; color: #37474F;" class="text-truncate ms-3">{{ $b['bodega'] }}</b>
                                                                </div>
                                                                <div class="col-2 text-end">
                                                                    <i style="font-size: 15px; color: #37474F;" class="bi bi-chevron-right me-3"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
        
                                            <div class="row">
                                                <div class="col-12 text-end">
                                                    <button style="background: #DAE0E5; color: #546E7A;" type="button" class="btn"><i class="bi bi-floppy"></i> Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--End modal body-->
                    </div>
                </div>
            </div><!--End modal cambiar bodega-->


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

            //Iniciar por defecto con algunos elementos ocultos
            iconClose.style.display = 'none';
            txtSearch.style.display = 'none';
            
            //Escucha de botones del panel main
            iconSearch.addEventListener('click',function(){
                statusBtnSearch = true;

                if(window.scrollY > maxHeightToScroll){
                    clickBtnSearchWithScroll();
                }
                else{
                    clickBtnSearchWithoutScroll();
                }
            });

            iconClose.addEventListener('click',function(){
                statusBtnClose = true;

                if(window.scrollY > maxHeightToScroll){
                    clickBtnCloseWithScroll();
                }
                else{
                    clickBtnCloseWithoutScroll();
                }
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
                menu.style.height        = menuHeightWithoutScroll;//Aumentamos la altura del menu
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
                            menu.style.height    = menuHeightWithoutScroll;//Aumentamos la altura del menu
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
