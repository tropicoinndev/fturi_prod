@extends('layouts.app')

@section('style')
    <style>
        .navbar-expand-md {/*Ocultamos el menu superior cuando esta en tamaño movil*/
            display: none;
        }
        body {
            background: #E0F2F1;
            font-family: sans-serif;
        }

        /*Menu superior*/
        #top-bar {
            position: fixed;
            top: 0;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            padding: 25px 25px;/*Padding normal*/ /*Arriba-Abajo, Izquierda-Derecha*/
            left: 0;
            width: 100%;
            background: #37474F;
            color: #fff;
            transition: padding 0.5s ease-in-out;
        }
        #top-bar.shrink {
            padding: 12px 20px;/*Reduccion del padding al hacer scroll*/ /*Arriba-Abajo, Izquierda-Derecha*/
        }
        /*---*/

        .bi-arrow-left, .bi-x-lg, .bi-search, .bi-three-dots-vertical {/*Iconos del header de flecha izquierda y equis*/
            color: #FAFAFA;
            font-size: 16px;
        }

        /*Menu inferior*/
        #bottom-bar {
            position: fixed;
            /*bottom: -115px;*//*Inicia oculto*/
            bottom: 0;/*Inicia visible*/
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            padding: 10px;
            left: 0;
            width: 100%;
            background: #37474F;
            color: #fff;
            transition: bottom 0.5s ease-in-out;
        }
        #bottom-bar.active {
            bottom: 0;/*Mostrar cuando se activa*/
        }
        /*---*/

        #content {
            /*Estilos para tu contenido principal*/
            /*Asegúrate de tener suficiente margen inferior para evitar superposición*/
            margin-top: 60px;/*Ajusta según la altura de tu menú superior*/
            margin-bottom: 100px;/*Ajusta según la altura de tu menú inferior*/
            padding: 17px;
        }

        .panel-main {/*Este es el panel de color blanco, sobre él se colocaran las card celestes*/
            border-radius: 20px;
            background: #FAFAFA;
            padding: 15px;
            width: 100%;
            height: auto;
        }
    </style>
@endsection

@section('content')
    <div style="margin-top: -8px;" id="aaa" class="container-fluid panel-main-opacity">
        <div class="row justify-content-center">

            <div id="top-bar">
                <div style="margin-bottom: -9px;" class="row"><!--Reducir un poco la altura de la fila-->

                    <div style="margin-top: -7px;" class="col-1">
                        <a href="javascript:void(0);"><i class="bi bi-arrow-left fs-4"></i></a>
                    </div>

                    <div class="col-8">
                        <h6 class="text-uppercase ms-2">Black And White</h6>
                    </div>

                    <div style="margin-top: -12px;" class="col-3 d-flex justify-content-end">
                        <button type="button" class="btn"><i class="bi bi-search"></i></button>

                        <div style="margin-right: -20px;" class="dropdown"><!--Colocar el margen al elemento que quedara de ultimo-->
                            <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 12px;" class="row">
                    <div class="col-12">
                        <input id="txtSearch" type="text" class="form-control form-control-sm rounded-pill" placeholder="Buscar por nombre de producto">
                    </div>
                </div>
            </div>

            <div id="content">
                <div class="panel-main">
                    <!-- Aquí va tu contenido principal -->
                    <p style="height: 80px;">1</p>
                    <p style="height: 80px;">2</p>
                    <p style="height: 80px;">3</p>
                    <p style="height: 80px;">4</p>
                    <p style="height: 80px;">5</p>
                    <p style="height: 80px;">6</p>
                    <p style="height: 80px;">7</p>
                    <p style="height: 80px;">8</p>
                    <p style="height: 80px;">9</p>
                    <p style="height: 80px;">10</p>
                    <p style="height: 80px;">11</p>
                    <p style="height: 80px;">12</p>
                    <p style="height: 80px;">13</p>
                    <p style="height: 80px;">14</p>
                    <p style="height: 80px;">15</p>
                </div>
            </div>

            <div id="bottom-bar">
                <div class="row text-center">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary rounded-5"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        let lastScrollTop = 0;
        let sensibilidad = 5;//5 - 100 Entre mas bajo el numero, mayor sensibilidad al desplazamiento

        let navbarHeightBottom = document.getElementById('bottom-bar').offsetHeight;

        window.addEventListener('scroll',function(){
            let bottomBar = document.getElementById('bottom-bar');
            let topBar    = document.getElementById('top-bar');

            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if(Math.abs(lastScrollTop - currentScroll) <= sensibilidad)
                return;

            if(currentScroll > lastScrollTop){//Scroll hacia abajo
                bottomBar.style.bottom = -navbarHeightBottom + 'px';//Variable negativa
                topBar.classList.add('shrink');
            }
            else{//Scroll hacia arriba
                bottomBar.style.bottom = '0';
                topBar.classList.remove('shrink');
            }
            
            lastScrollTop = currentScroll;
        });
    </script>
@endsection
