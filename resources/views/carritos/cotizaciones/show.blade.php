@extends ('layouts.admin')
@section('contenido')

{!!Form::open(array('url'=>'carritos/despacho','method'=>'POST','autocomplete'=>'off'))!!}
{{Form::Token()}}

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Resumen del evento {{$evento[0]->id}}</h3>
    <hr></hr>

    <label>Estado: </label>
    <h4 style="color:orange"> <strong>Pendiente</strong></h4>

    <hr></hr>
    <h4>Datos del evento<input hidden name="id_evento_" value="{{$evento[0]->id}}"></h4>
  </div>
</div>

<div class="row ">

  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">

    <div class="form-group">
      <label for="nombre_cliente">Nombre cliente</label>
      <h4>{{$evento[0]->nombre_cliente}}</h4>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="direccion_cliente">Dirección</label>
      <h4>{{$evento[0]->direccion}}</h4>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="telefono_cliente">Teléfono</label>
        <h4>{{$evento[0]->contacto}}</h4>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="fecha_cliente">Fecha</label>
      <h4>{{$evento[0]->fecha_hora}}</h4>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="email_cliente">E-mail</label>
        <h4>{{$evento[0]->email}}</h4>
    </div>
  </div>

</div>
<div class="row">
  <div class="col-lg-5 col-md-5 col-sm-12">
    <hr></hr>
    <h4>Productos del evento</h4>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#F1948A">
          <th>Productos</th>
          <th>Cantidad</th>
          <th>Precio unidad</th>
          <th>Sub total</th>
        </thead>
        @foreach($productos as $prod)
        <tr>
          <td>{{$prod->nombre}}</td>
          <td>{{$prod->cantidad}} unidades</td>
          <td>${{$prod->precio}}</td>
          <td>${{$prod->precio * $prod->cantidad}}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4">
    <hr></hr>
    <h4>Implementos necesarios</h4>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style= "background-color:#7FB3D5">
          <th class="col-lg-2 col-md-2 col-sm-2">Implemento</th>
          <th class="col-lg-2 col-md-2 col-sm-2">Cantidad</th>
        </thead>
        @foreach($base as $bs)
        <tr>
          <td>{{$bs->base}}</td>
          <td>{{$bs->sum}} unidades (mín)</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3">
    <hr></hr>
    <h4>Extras del evento</h4>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#F9E79F">
          <th class="col-lg-2 col-md-2 col-sm-2">Extras</th>
          <th class="col-lg-2 col-md-2 col-sm-2">Cantidad</th>
        </thead>
        @foreach($extras as $ext)
        <tr>
          <td>{{$ext->nombre}}</td>
          <td>{{$ext->cantidad}}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <hr></hr>
    <h4>Ingredientes totales del evento</h4>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#7DCEA0">
          <th>Ingredientes</th>
          <th>Cantidad sugerida</th>
          <th>Cantidad usada</th>
          <th>Unidades</th>
        </thead>
        @foreach($ingredientes as $ingr)
        <tr>
          <td>{{$ingr->nombre}}</td>
          <td>{{$ingr->sum}} {{$ingr->unidad}}</td>

          @if($evento[0]->condicion == 2)
          <td><input class="form-control" disabled value="{{$ingr->cant}}"></td>
          @else
          <td><input class="form-control" disabled value="No asignado aun"></td>
          @endif
          <td>{{$ingr->uni_inv}}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">

  <h4>Costos del evento</h4>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#D2B4DE">
          <th>Costo total evento</th>
          <th>Margen de utilidad (%)</th>
          <th>Monto a cobrar evento</th>
        </thead>
        <tr>
          <td><input name="total_pre" class="form-control" id="val2" value="{{$total[0]->sum}}"  disabled></td>
          <td><input name="margen" class="form-control" disabled placeholder="%" id="val1" onkeyup="calculo_total(this.value)" required></td>
          <td><input name="total_final" class="form-control" placeholder="" id="Display" disabled></td>
        </tr>
      </table>
    </div>
  </div>
</div>
@if($evento[0]->condicion == 2)

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">

    <h4>Trabajadores del evento</h4>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#CCD1D1">
          <th>Nombre</th>
          <th>Pago</th>
        </thead>
        @foreach($trabajadores as $tra)
        <tr>
          <td><input class="form-control" value="{{$tra->nombre}} {{$tra->apellido}}"  disabled></td>
          <td><input class="form-control" value="{{$tra->monto}}" disabled></td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>
@endif

{!!Form::close()!!}
@endsection
