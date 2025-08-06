@extends('layouts.app')

@section('style')
    <style>
        .navbar-expand-md {/*Ocultamos el menu superior que trae por defecto Laravel cuando esta en tamaño movil*/
            display: none;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: hidden;/*Evita las barras de desplazamiento derecha y abajo*/
            font-family: Arial, sans-serif;
        }
        main {
            margin-top: -8px !important;
            bottom: 0;
        }

        /*Imagen de fondo*/
        .banner {
            width: 100vw;
            height: 100vh;/*Asegura que el banner ocupe toda la altura visible del viewport*/
            background: #263238 url({{ asset('images/bbb.jpg') }}) center center no-repeat;
            background-size: cover;
            /*display: flex;*/
            justify-content: center;
            align-items: center;
        }

        .logoTropico {
            max-width: 17%;
            max-height: 17%;
            margin-top: 3%;
            margin-left: 44%;
        }

        /*Reloj dinámico*/
        .clock {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .date {
            font-size: 1.2rem;
            color: #807878dc;
        }
    </style>
@endsection

@section('content')
    <div class="banner">
        <img src="{{ asset(env('logo_lg')) }}" alt="logo" class="logoTropico">

        <div class="row align-items-center p-5 mt-1">
            <div class="col-1 col-sm-1 col-md-2"></div>

            <div class="col-12 col-sm-5 col-md-3 text-end">
                <div class="input-group mb-3">
                    <button class="btn btn-danger" type="button" id="button-addon1"><span class="mdi mdi-close-thick"></span></button>
                    <input type="password" class="form-control" placeholder="PIN" aria-label="Example text with button addon" aria-describedby="button-addon1">
                    <button  class="btn btn-success" type="button" id="button-addon1"><span class="mdi mdi-lock"></span></button>
                </div>
            </div>

            <div class="col-1 col-sm-1 col-md-2"></div>

            <div class="col-12 col-sm-5 text-start">
                <p>
                    <div class="clock" id="hclock"></div>
                    <div class="date" id="fclock"></div>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            updateClock();
            setInterval(updateClock, 1000);//Actualizar cada segundo
        });

        function updateClock(){
            var today = new Date();
            var hr    = today.getHours();
            var min   = today.getMinutes();
            var sec   = today.getSeconds();
            var day   = today.getDay();
            var date  = today.getDate();
            var month = today.getMonth();
            var year  = today.getFullYear();

            //Formato de la hora
            var ampm = (hr < 12) ? "AM" : "PM";
            hr  = (hr == 0) ? 12 : hr;
            hr  = (hr > 12) ? hr - 12 : hr;
            hr  = checkTime(hr);
            min = checkTime(min);
            sec = checkTime(sec);

            //Actualizar el HTML
            document.getElementById('hclock').innerHTML = hr + ":" + min + ":" + sec + " " + ampm;
            document.getElementById('fclock').innerHTML = getDay(day) + ", " + date + " " + getMonth(month) + " " + year;
        }

        function getDay(dayIndex){
            var days = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
            return days[dayIndex];
        }
        function getMonth(monthIndex) {
            var months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
            return months[monthIndex];
        }
        function checkTime(i){
            if(i < 10)
                i = "0" + i;

            return i;
        }
    </script>
@endsection
