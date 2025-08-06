@extends('layouts.app')

@section('style')
    <style>
        .navbar-expand-md{/*Ocultamos el menu superior cuando esta en tamaño movil*/
            display: none;
        }
        body{
            background: #E0F2F1;
            font-family: sans-serif;
        }

        .contenedor {
        position: relative;
      }

      .menuSinScroll {
        position: relative;
        height: 300px;
        background: #fafafa;
        color: #000;
      }
      .menuConScroll {
        background: #000;
        color: #fafafa;
        position: fixed;
        height: 30px;
      }
      .ocultarAlHacerScroll {
        visibility: hidden;
      }
      header {
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 10px 0;
      }

      nav {
        background-color: #eee;
        padding: 10px 0;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1000;
      }

      nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        text-align: center;
      }

      nav li {
        display: inline-block;
        margin-right: 20px;
      }

      nav a {
        text-decoration: none;
        color: #333;
        font-weight: bold;
        font-size: 16px;
      }

      main {
        padding: 20px;
      }

      section {
        margin-bottom: 400px; /* Ajustar según la altura del menú */
      }
    </style>
@endsection

@section('content')
    <div id="appComandasMovil" class="container-fluid">
        <div class="row justify-content-center">

            <div class="contenedor">
                <header>
                  <h1>Información completa</h1>
                </header>
                <nav id="menu" class="menuSinScroll">
                  <ul>
                    <li><a href="#seccion1">Sección 1</a></li>
                    <li><a href="#seccion2">Sección 2</a></li>
                    <li><a href="#seccion3">Sección 3</a></li>
                  </ul>
                  <p id="masInfo">MAS INFORMACION QUE SE OCULTARA AL HACER SCROLL</p>
                </nav>
                <main>
                  <!-- Contenido de la página -->
                  <section id="seccion1">
                    <h2>Sección 1</h2>
                    <p>Contenido de la sección 1.</p>
                  </section>
                  <section id="seccion2">
                    <h2>Sección 2</h2>
                    <p>Contenido de la sección 2.</p>
                  </section>
                  <section id="seccion3">
                    <h2>Sección 3</h2>
                    <p>Contenido de la sección 3.</p>
                  </section>
                </main>
              </div>
            
        </div>
    </div>
@endsection

@section('script')
    <script>
        function scroll() {
        window.addEventListener("scroll", function () {
          console.log("Scroll", window.scrollY);

          var menu = document.getElementById("menu");
          var ocultar = document.getElementById("masInfo");
          if (window.scrollY > 50) {
            menu.classList.add("menuConScroll");
            menu.classList.remove("menuSinScroll");
            ocultar.classList.add("ocultarAlHacerScroll");
          } else {
            menu.classList.add("menuSinScroll");
            menu.classList.remove("menuConScroll");
            ocultar.classList.remove("ocultarAlHacerScroll");
          }
        });
      }
      scroll();
    </script>
@endsection

