@extends('layouts.app')

@section('style')
    <style>
        .navbar-expand-md {/*Ocultamos el menu superior cuando esta en tamaño movil*/
            display: none;
        }
        #movil {
            display: block;
        }
        #desktop {
            display: none;
        }
        body {
            background-color: #E0F2F1;
            font-family: sans-serif;
        }
        .menuSinScroll {/*Por defecto, su posicion es fijo en la parte superior*/
            background: #4DB6AC;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            height: 110px;
        }
        .menuConScroll {
            position: fixed;
            top: 0;
            background: #4DB6AC;
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
        .panel-main {/*Este es el panel de color blanco, sobre él se colocaran las card blancas*/
            border-radius: 20px;
            background: #FAFAFA;
            padding: 10px;
            width: 93% !important;
            margin-top: -30px !important;
        }
        .panel-card {/*Estas son las card de color blancas donde se muestra la información*/
            background: #fff;
            color: #455A64;
            border: solid 1px #A7A4A4;
            border-radius: 8px;
            height: 120px;
        }
        .panel-card:hover {/*El color de fondo de la caja cambiara la hacer hover*/
            background: #4db6acd3;
            color: #fff;
        }
        #txtPinBodega {/*Por defecto no se mostrara el input para ingresar el pin, solo se mostrara al hacer hover*/
            display: none;
        }
        .panel-card:hover #txtPinBodega {/*Aqui ya se hizo hover sobre la caja, por lo tanto, ya se mostrará el input del pin*/
            display: block;
        }
        .panel-card:hover #titulosCaja {/*Al hacer hover sobre la caja, los titulos reduciran su margen superior para dar un poco mas de espacio al input del pin*/
            margin-top: -12px;
        }
        /*Resolucion movil o tablet*/
        @media(max-width: 767px) or (max-width: 991px){/*Se ocultara el div que contiene el abecedario cuando el tamaño sea un telefono*/
            
        }
        /*Resolucion desktop*/
        @media (min-width: 992px) {/*Se mostrará el div que contiene el abecedario cuando el tamaño sea un escritorio*/
            .caja {
                min-height: 200px;
                transition: background 1s ease-out;
            }
            .pin {
                transition: opacity 1s ease-out;
                opacity: 0;
                height: 0;
                overflow: hidden;
            }
            .caja:hover .pin {
                opacity: 1;
                height: auto;
            }
            .caja:hover {
                background: #009688;
                color: #fff;
            }
        }
    </style>
@endsection

@section('content')
    <div id="appLoginCajas" class="container">
        <div id="desktop" style="margin-top: 65px;">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="min-height: 450px;">
    
                        <div class="card-body">
                            <h3 class="card-title">CAJAS</h3>
                            <p class="card-text">
                            <div class="col-12">
                                <x-message></x-message>
                            </div>
                            <div class="row mt-5">
                                @foreach ($cajas as $c)
                                    @isset($c->cajas->caja)
                                        <div class="col-4 mb-3">
                                            <div class="card caja border-1 border-dark">
                                                <div class="card-body d-flex">
                                                    <div class="col align-self-center text-center">
                                                        <div class="col-12 h2 card-title text-uppercase">
                                                            {{ $c->cajas->caja }}
                                                        </div>
                                                        <div class="card-text mb-2">
                                                            CAJA
                                                        </div>
                                                        <div class="card-text pin">
                                                            <form action="{{ route('cajas.auth') }}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="caja"
                                                                    value="{{ \Crypt::encryptString($c->id) }}">
                                                                <div class="input-group">
                                                                    <input class="form-control border-dark" type="password"
                                                                        name="pin" placeholder="PIN" autocomplete="off">
                                                                    <button class="input-group-text" type="submit">Acceder</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
    
                                                </div>
                                            </div>
                                        </div>
                                    @endisset
                                @endforeach
                            </div>
                            </p>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>

        <div id="movil" style="margin-top: 16px;">
            <div class="row justify-content-center" style="margin-top: -60px;">
            

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
                                <h1 style="margin-top: 8px;" class="title ms-2">LISTADO DE CAJAS</h1>
                                <div id="titles">
                                    <h2 style="margin-top: -3px;" class="subtitle text-uppercase ms-2 mb-3">Hola, {{ auth()->user()->name }}</h2>
                                </div>
                            </div>
                            <div class="col-2 text-end">
                                <i id="iconClose" class="bi bi-x-lg me-1"></i>
                                <i id="iconSearch" class="bi bi-search me-1"></i>
                            </div>
                        </div>
    
                        <div class="row mt-2">
                            <div class="col-12">
                                <input v-model="txtBusqueda" id="txtSearch" type="text" class="form-control rounded-pill" placeholder="Buscar por nombre de caja">
                            </div>
                        </div>
                    </div>
                </div><!-- end panel header-->
    
    
                <!-- panel main -->
                <div class="panel-main">
                    <p style="color: #37474F; font-size: 12px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;">INICIAR SESIÓN EN UNA CAJA</p>
                    
                    <div class="row">
                        <div class="col-12">
                            <x-message></x-message>
                        </div>
                    </div>

                    {{-- @{{ cajas }} --}}
                    <div class="row p-2">
                        <div v-for="c in getCajas" class="col-12 mb-3">
                            <div class="panel-card p-4">
                                <div class="text-center">
                                    <div id="titulosCaja">
                                        <p style="font-size: 16px; font-weight: bold; margin-bottom: 1px;">{{--@{{ c.cajas.cid }} --}} @{{ c.cajas.caja }}</p>
                                        <p style="font-size: 11px;">CAJA</p>
                                    </div>

                                    <div id="txtPinBodega">
                                        <form action="{{ route('cajas.auth.app') }}" method="post">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <input type="hidden" name="caja" :value="c.cajas.cid">

                                                <input type="password" name="pin" class="form-control" placeholder="PIN" aria-label="PIN" autocomplete="off" aria-describedby="basic-addon2">
                                                <button style="background: #f1f1f1f5; color: #53585C;" class="btn" type="submit" id="button-addon2">
                                                    {{-- <a href="{{ route('listar.comandas') }}" style="text-decoration: none;">
                                                        Acceder
                                                    </a> --}}
                                                    Acceder
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end panel main -->
    
    
            </div>
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

        //Vue
        var app = new Vue({
            el: '#appLoginCajas',
            data: {
                cajas: @json($cajas),
                txtBusqueda: '',
            },
            methods: {

            },
            computed: {
                getCajas: function(){
                    let regx = new RegExp((this.txtBusqueda).toLowerCase());
                    return this.cajas.filter(c => regx.test(c.cajas.caja.toLowerCase()) || regx.test(c.id));
                },
            }
        });
    </script>
@endsection
