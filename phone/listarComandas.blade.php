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
        .bi-x-lg, .bi-search {/*Iconos del header de flecha izquierda y equis*/
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
            border-bottom: solid 5px #BBDEFB;
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
        @media(max-width: 767px){/*Se cambiara el tamaño de la modal cuando la resolucion sea un telefono*/
            .modal-content {
                left: 0;    /*Agregamos esta línea para asegurar que la modal ocupe todo el ancho de la pantalla*/
                width: 100%;/*Establecemos el ancho al 100% para dispositivos móviles*/
            }
        }
        @media(min-width: 768px) and (max-width: 991px){/*Se cambiara el tamaño de la modal cuando la resolucion sea una tablet*/
            .modal-content {
                margin: 0 auto;/*Centramos la modal en dispositivos de tablet*/
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
                        <div class="col-10">
                            <h1 style="margin-top: 8px;" class="title ms-2">BLACK AND WHITE</h1>
                            <div id="titles">
                                <h2 style="margin-top: -6px;" class="subtitle ms-2 mb-3">Hola, ADMIN.</h2>
                                <h1 class="title ms-2">TURNO 2</h1>
                                <h2 style="margin-top: -6px;" class="subtitle ms-2 mb-3">Apertura hace 1 sec · cierre en 5 horas</h2>
                            </div>
                        </div>
                        <div class="col-2 text-end">
                            <i id="iconClose" class="bi bi-x-lg me-1"></i>
                            <i id="iconSearch" class="bi bi-search me-1"></i>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <input id="txtSearch" type="text" class="form-control rounded-pill" placeholder="Buscar por numero de mesa">
                        </div>
                    </div>
                </div>
            </div><!-- end panel header-->


            <!-- panel main -->
            <div class="panel-main">
                <p style="color: #37474F; font-size: 12px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;">MESAS AGREGADAS</p>

                <div class="row mt-1 mb-5">
                    @php
                        $comandas = [
                            ['mesa'=>1,  'cliente'=>'JORGE LUIS CASTILLO MARTINEZ',   'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>2,  'cliente'=>'KIBERLY ARILENE PEÑA ORTIZ',     'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>3,  'cliente'=>'KAREN HERNANDEZ BENITEZ',        'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>4,  'cliente'=>'JUAN PEREZ DOMINGUEZ GUTIERREZ', 'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>5,  'cliente'=>'ANDERSON ELI CAMPOS MOREIRA',    'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>6,  'cliente'=>'LORENA ALEJANDRA ARAYA',         'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>7,  'cliente'=>'STEPHANY MICHELLE AMAYA',        'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>8,  'cliente'=>'DIANA LETICIA CRUZ MARTINEZ',    'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>9,  'cliente'=>'EDGAR APARICIO MONTECINOS',      'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>10, 'cliente'=>'VICENTE FERNANDEZ',              'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>11, 'cliente'=>'JORGE LUIS CASTILLO MARTINEZ',   'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>12, 'cliente'=>'KIBERLY ARILENE PEÑA ORTIZ',     'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>13, 'cliente'=>'KAREN HERNANDEZ BENITEZ',        'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>14, 'cliente'=>'JUAN PEREZ DOMINGUEZ GUTIERREZ', 'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>15, 'cliente'=>'ANDERSON ELI CAMPOS MOREIRA',    'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>16, 'cliente'=>'LORENA ALEJANDRA ARAYA',         'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>17, 'cliente'=>'STEPHANY MICHELLE AMAYA',        'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>18, 'cliente'=>'DIANA LETICIA CRUZ MARTINEZ',    'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>19, 'cliente'=>'EDGAR APARICIO MONTECINOS',      'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>20, 'cliente'=>'VICENTE FERNANDEZ',              'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>21, 'cliente'=>'VICENTE FERNANDEZ',              'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>22, 'cliente'=>'JORGE LUIS CASTILLO MARTINEZ',   'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>23, 'cliente'=>'KIBERLY ARILENE PEÑA ORTIZ',     'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>24, 'cliente'=>'KAREN HERNANDEZ BENITEZ',        'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>25, 'cliente'=>'JUAN PEREZ DOMINGUEZ GUTIERREZ', 'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>26, 'cliente'=>'ANDERSON ELI CAMPOS MOREIRA',    'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>27, 'cliente'=>'LORENA ALEJANDRA ARAYA',         'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>28, 'cliente'=>'STEPHANY MICHELLE AMAYA',        'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>29, 'cliente'=>'DIANA LETICIA CRUZ MARTINEZ',    'creacion'=>'Creada hace 2 dos horas'],
                            ['mesa'=>30, 'cliente'=>'EDGAR APARICIO MONTECINOS',      'creacion'=>'Creada hace 2 dos horas']
                        ];
                    @endphp

                    @foreach($comandas as $c)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-2">
                            <a href="{{ route('productos.comandas') }}" style="text-decoration: none;">
                                <div class="panel-card">
                                    <div class="row">
                                        <p style="text-align: center; color: #263238; margin-bottom: 3px;">#{{ $c['mesa'] }}</p>
                                        <p style="font-size: 12px; color: #546E7A; margin-bottom: 1px;" class="text-truncate">{{ $c['cliente'] }}</p>
                                        <p style="font-size: 10px; color: #37474F; margin-bottom: -3px;">{{ $c['creacion'] }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div><!-- end panel main -->



            <!-- panel bottom -->
            <div class="panel-fixed">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="btn-group" role="group" aria-label="Basic example">

                            <button type="button" class="btn btn-primary btn-lg rounded-start-circle" data-bs-toggle="modal" data-bs-target="#modalMesaOcupada">
                                <i class="bi bi-person-gear"></i>
                            </button>

                            <span style="background-color: #0d6efd; padding: 3px;" data-bs-toggle="modal" data-bs-target="#modalCrearNuevaMesa">
                                <button style="height: 50px; background-color: #FAFAFA;" type="button" class="btn btn-outline-primary btn-lg rounded-start-circle rounded-end-circle">
                                    <i class="bi bi-plus-lg" style="color: #0d6efd;"></i>
                                </button>
                            </span>

                            <button id="btnSearch" type="button" class="btn btn-primary btn-lg rounded-end-circle">
                                <a href="{{ route('listar.cajas') }}" style="text-decoration: none;">
                                    <i style="color: white;" class="bi bi-box-arrow-right"></i>
                                </a>
                            </button>

                        </div>
                    </div>
                </div>
            </div><!-- end panel bottom -->



            <!-- Modal crear nueva mesa -->
            <div class="modal fade" id="modalCrearNuevaMesa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="position-relative mb-4"><!--Div relativo para posicionar los iconos a la izquierda y derecha-->
                                <div class="position-absolute top-0 start-0" style="margin-top: 4px;">
                                    <h1 style="color: #FAFAFA;" class="modal-title fs-6 ms-3" id="staticBackdropLabel">CREAR NUEVA MESA</h1>
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
                                        <div style="margin-top: 10px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card p-3">
                                            <p style="color: #263238; margin-bottom: 2px;">Numero de mesa</p>
                                            <input style="border-color: #26C6DA;" type="number" class="form-control mb-2" placeholder="Ingrese el numero de mesa">
        
                                            <div class="row align-items-center">
                                                <div class="col-6 mt-3">
                                                    <p style="color: #0097A7;"><i class="bi bi-check-lg"></i> Disponible</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <button style="background: #DAE0E5; color: #546E7A;" type="button" class="btn"><i class="bi bi-plus-lg"></i> Agregar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div><!--End modal crear nueva mesa-->

            <!-- Modal mesa ocupada -->
            <div class="modal fade" id="modalMesaOcupada" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="position-relative mb-4"><!--Div relativo para posicionar los iconos a la izquierda y derecha-->
                                <div class="position-absolute top-0 start-0" style="margin-top: 4px;">
                                    <h1 style="color: #FAFAFA;" class="modal-title fs-6 ms-3" id="staticBackdropLabel">CREAR NUEVA MESA</h1>
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
                                        <div style="margin-top: 10px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card p-3">
                                            <p style="color: #263238; margin-bottom: 2px;">Numero de mesa</p>
                                            <input style="border-color: #26C6DA;" type="number" class="form-control mb-2" placeholder="1">
        
                                            <div class="row align-items-center">
                                                <div class="col-6 mt-3">
                                                    <p style="color: #FF7043;"><i class="bi bi-exclamation-circle"></i> Ocupada</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <button style="background: #DAE0E5; color: #546E7A;" type="button" class="btn"><i class="bi bi-plus-lg"></i> Agregar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div><!--End modal mesa ocupada-->

            <!-- Modal mesa sin asignar -->
            <div class="modal fade" id="modalMesaSinAsignar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="position-relative mb-4"><!--Div relativo para posicionar los iconos a la izquierda y derecha-->
                                <div class="position-absolute top-0 start-0" style="margin-top: 4px;">
                                    <h1 style="color: #FAFAFA;" class="modal-title fs-6 ms-3" id="staticBackdropLabel">CREAR NUEVA MESA</h1>
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
                                        <div style="margin-top: 10px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card p-3">
                                            <p style="color: #263238; margin-bottom: 2px;">Numero de mesa</p>
                                            <input style="border-color: #888888;" type="number" class="form-control mb-2" placeholder="Nº de mesa">
        
                                            <div class="row">
                                                <div class="col-12 text-end mt-3">
                                                    <button style="background: #DAE0E5; color: #546E7A;" type="button" class="btn"><i class="bi bi-plus-lg"></i> Agregar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div><!--End modal mesa sin asignar-->


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
