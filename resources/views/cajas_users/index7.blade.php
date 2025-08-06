@extends('layouts.panel-cajas')

@section('content')

<div class="container">
  <h4>CAJAS ACTIVAS LISTAS PARA INICIAR SESION</h4>
  <div class="row">
    <div class="col-md-4">
      <h6>USUARIO CON PIN CONFIGURADO</h6>
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title center">TROPICLUB</h5>
          <p class="card-text">PIN</p>
          
          <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
      </div>
    </div>
  
    <div class="col-md-4">
    <h6>USUARIO SIN PIN CONFIGURADO</h6>
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
      </div>
    </div>
  
  
  </div>
</div>
@endsection