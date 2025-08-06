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
        }
        .letters {/*Se usa unicamente para colocar/centrar las letras dentro del boton*/
            margin-left: -1px;
        }
        @media(max-width: 767px){/*Se ocultara el div que contiene el abecesario cuando el tamaño sea un telefono*/
            #abecedarioDiv {
                display: none;
            }
        }
        @media (min-width: 768px) and (max-width: 991px){/*Se mostrara el div que contiene el abecesario cuando el tamaño sea una tablet*/
            #abecedarioDiv {
                display: block;
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
                            <a href="{{ route('productos.comandas') }}" style="text-decoration: none;">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                        <div class="col-9">
                            <h1 style="margin-top: 8px;" class="title ms-2">CATEGORIAS</h1>
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
                            <input id="txtSearch" type="text" class="form-control rounded-pill" placeholder="Buscar por categoria">
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
                <p style="color: #37474F; font-size: 12px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;">SELECCIONE UNA CATEGORIA</p>

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
                            <a href="{{ route('listar.categorias.precios') }}" style="text-decoration: none;">
                                <div class="panel-card">
                                    <div class="row">
                                        <div class="col-10">
                                            <b style="font-size: 12px; color: #37474F;" class="text-truncate ms-3">{{ $c['producto'] }}</b>
                                        </div>
                                        <div class="col-2 text-end">
                                            <i style="font-size: 15px; color: #37474F;" class="bi bi-chevron-right me-3"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div><!-- end panel main -->


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
                txtSearch.focus();
            }
            function clickBtnCloseWithoutScroll(){
                menu.style.height        = '110px';//Disminuimos la altura del menu
                titles.style.display     = 'block';
                iconSearch.style.display = 'block';
                iconClose.style.display  = 'none';
                txtSearch.style.display  = 'none';
                txtSearch.focus();
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
