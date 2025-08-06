@extends('layouts.app')

@section('style')
    <style>
        .navbar-expand-md {/*Ocultamos el menu superior cuando esta en tamaño movil*/
            display: none;
        }
        .container {
            display: none;/*Se oculta el container que esta dentro de las seccion list*/
        }
        #movil {
            display: block;
        }
        #abecedarioDiv {
            display: none;
        }
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
            width: 92% !important;
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
        /*Resolucion movil o tablet*/
        @media(max-width: 767px) or (max-width: 991px){/*Se ocultara el div que contiene el abecesario cuando el tamaño sea un telefono*/
            
        }
        /*Resolucion tablet*/
        @media (min-width: 768px){/*Se mostrara el div que contiene el abecesario cuando el tamaño sea una tablet*/
            #abecedarioDiv {
                display: block;
            }
        }
        /*Resolucion desktop*/
        @media (min-width: 992px){/*Se mostrará el div que contiene el abecedario cuando el tamaño sea un escritorio*/
            
        }
    </style>
@endsection

<div id="appListarCategorias">
    <div id="movil" style="margin-top: 24px;">
        <div class="row justify-content-center" style="margin-top: -24px;">
                
    
            <!-- panel header -->
            <div class="menuSinScroll" id="menu">
                <div class="container-fluid mt-3">
                    <div class="row">
                        <div class="col-1">
                            <a href="{{ route('comandas.movil.index') }}" style="text-decoration: none;">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                        <div class="col-9">
                            <h1 style="margin-top: 8px;" class="title ms-2">CATEGORIAS</h1>
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
                            <input v-model="txtBusqueda" id="txtSearch" type="text" class="form-control rounded-pill" placeholder="Buscar por categoria">
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
                                <button @click="txtBusqueda = 'A'" class="btn abecedario"><span class="letters">A</span></button>
                                <button @click="txtBusqueda = 'B'" class="btn abecedario"><span class="letters">B</span></button>
                                <button @click="txtBusqueda = 'C'" class="btn abecedario"><span class="letters">C</span></button>
                                <button @click="txtBusqueda = 'D'" class="btn abecedario"><span class="letters">D</span></button>
                                <button @click="txtBusqueda = 'E'" class="btn abecedario"><span class="letters">E</span></button>
                                <button @click="txtBusqueda = 'F'" class="btn abecedario"><span class="letters">F</span></button>
                                <button @click="txtBusqueda = 'G'" class="btn abecedario"><span class="letters">G</span></button>
                                <button @click="txtBusqueda = 'H'" class="btn abecedario"><span class="letters">H</span></button>
                                <button @click="txtBusqueda = 'I'" class="btn abecedario"><span class="letters">I</span></button>
                                <button @click="txtBusqueda = 'J'" class="btn abecedario"><span class="letters">J</span></button>
                                <button @click="txtBusqueda = 'K'" class="btn abecedario"><span class="letters">K</span></button>
                                <button @click="txtBusqueda = 'L'" class="btn abecedario"><span class="letters">L</span></button>
                                <button @click="txtBusqueda = 'M'" class="btn abecedario"><span class="letters">M</span></button>
                                <button @click="txtBusqueda = 'N'" class="btn abecedario"><span class="letters">N</span></button>
                                <button @click="txtBusqueda = 'Ñ'" class="btn abecedario"><span class="letters">Ñ</span></button>
                                <button @click="txtBusqueda = 'O'" class="btn abecedario"><span class="letters">O</span></button>
                                <button @click="txtBusqueda = 'P'" class="btn abecedario"><span class="letters">P</span></button>
                                <button @click="txtBusqueda = 'Q'" class="btn abecedario"><span class="letters">Q</span></button>
                                <button @click="txtBusqueda = 'R'" class="btn abecedario"><span class="letters">R</span></button>
                                <button @click="txtBusqueda = 'S'" class="btn abecedario"><span class="letters">S</span></button>
                                <button @click="txtBusqueda = 'T'" class="btn abecedario"><span class="letters">T</span></button>
                                <button @click="txtBusqueda = 'U'" class="btn abecedario"><span class="letters">U</span></button>
                                <button @click="txtBusqueda = 'V'" class="btn abecedario"><span class="letters">V</span></button>
                                <button @click="txtBusqueda = 'W'" class="btn abecedario"><span class="letters">W</span></button>
                                <button @click="txtBusqueda = 'X'" class="btn abecedario"><span class="letters">X</span></button>
                                <button @click="txtBusqueda = 'Y'" class="btn abecedario"><span class="letters">Y</span></button>
                                <button @click="txtBusqueda = 'Z'" class="btn abecedario"><span class="letters">Z</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <p style="color: #37474F; font-size: 12px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;">SELECCIONE UNA CATEGORIA</p>
    
                {{-- @{{ categorias }} --}}
                <div class="row mt-1">
                    <div v-for="cat in getCategorias" class="col-12 mb-2">
                        <a :href="'/categorias/precios/movil/'+cat.id" style="text-decoration: none;">
                            <div class="panel-card">
                                <div class="row">
                                    <div class="col-10">
                                        <b style="font-size: 12px; color: #37474F;" class="text-truncate text-uppercase ms-3">@{{ cat.categoria }}</b>
                                    </div>
                                    <div class="col-2 text-end">
                                        <i style="font-size: 15px; color: #37474F;" class="bi bi-chevron-right me-3"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div><!-- end panel main -->
    
    
        </div>
    </div>
</div>



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
            el: '#appListarCategorias',
            data: {
                categorias: @json($p),
                txtBusqueda: '',
            },
            methods: {

            },
            computed: {
                getCategorias: function(){
                    let regx = new RegExp((this.txtBusqueda).toLowerCase());
                    //Se usa la propiedad 'data' en el filter, ya que la variable $p que viene desde el controlador no es un array, sino un objeto de paginador de Laravel.
                    //Por lo tanto, ya se puede recorrer el array en el v-for.
                    return this.categorias.data.filter(c => regx.test(c.categoria.toLowerCase()) || regx.test(c.id));
                },
            }
        });
    </script>
@endsection
