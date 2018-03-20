@extends ('layouts.admin')
@section('contenido')

{!!Form::open(array('url'=>'carritos/despacho','method'=>'POST','autocomplete'=>'off'))!!}
{{Form::Token()}}

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Resumen del evento {{$evento[0]->id}}</h3>
    <hr></hr>
    @if($evento[0]->condicion == 1)
    <label>Estado: </label>
    <h4 style="color:orange"> <strong>Pendiente</strong></h4>
    @endif
    @if($evento[0]->condicion == 2)
    <label>Estado: </label>
    <h4 style="color:green"> <strong>Despachado</strong></h4>
    @endif
    @if($evento[0]->condicion == 3)
    <label>Estado: </label>
    <h4 style="color:grey"> <strong>Ejecutado</strong></h4>
    @endif
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
  <div class="col-lg-12 col-md-12 col-sm-12">
    <hr></hr>
    <h4>Productos del evento</h4>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#F1948A">
          <th>Productos</th>
          <th>Cantidad</th>
          <th>Precio costo</th>
          <th>Total costo</th>
          <th>Precio neto</th>
          <th>IVA por pagar</th>
          <th>Total</th>
        </thead>
        <tfoot>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th><h4><strong>Total:</strong></h4></th>
          @if($evento[0]->condicion == 2)
          <th><input class="form-control" readonly="readonly" value="{{$evento_detalle[0]->total_productos_iva}}"></th>
          <th><input class="form-control" readonly="readonly" value="{{$evento_detalle[0]->total_productos}}"></th>
          @endif
        </tfoot>

        <?php $i = 0 ?>

        @foreach($productos as $prod)
        <tr>
          <td>{{$prod->nombre}}</td>
          <td><input class="form-control" id="cant_prod_{{$i}}" value="{{$prod->cantidad}}" readonly="readonly"></td>
          <td>${{$prod->precio}}</td>
          <td>${{$prod->precio * $prod->cantidad}}</td>
          <th><input readonly="readonly" class="form-control" value="{{$prod->precio_a_cobrar}}"></th>
          <th><input readonly="readonly" class="form-control" value="{{$prod->precio_a_cobrar * 0.19 * $prod->cantidad}}"></th>
          <th><input readonly="readonly" class="form-control" value="{{$prod->precio_a_cobrar * $prod->cantidad}}"></th>
        </tr>
        <?php $i++ ?>
        @endforeach
      </table>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6">
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
  <div class="col-lg-6 col-md-6 col-sm-6">
    <hr></hr>
    <h4>Extras del evento</h4>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#F9E79F">
          <th class="col-lg-2 col-md-2 col-sm-2">Extras</th>
          <th class="col-lg-2 col-md-2 col-sm-2">Precio</th>
        </thead>
        @foreach($extras as $ext)
        <tr>
          <td>{{$ext->valor}}</td>
          <td>{{$ext->precio}}</td>
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
          <th>Unidades</th>
        </thead>
        @foreach($ingredientes as $ingr)
        <tr>
          <td>{{$ingr->nombre}}</td>
          @if($ingr->unidad == "gramos" || $ingr->unidad == "unidad")
          @if($ingr->unidad == "gramos")
          <td>{{$ingr->sum / 1000}} kg</td>
          @endif
          @if($ingr->unidad == "unidad")
          <td>{{round($ingr->sum)}} unidades</td>
          @endif
          @else
          <td>{{$ingr->sum}} unidades</td>
          @endif
          <td>{{$ingr->unidad}}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6">

  <h4>Costos del evento</h4>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#D2B4DE">
          <th>Gasto en extras</th>

          <th>Precio evento</th>
        </thead>
        <tr>
          <td><input name="extra_movil" class="form-control" value="{{$evento_detalle[0]->gasto_extra}}"></td>

          <td><input name="total_final" class="form-control" value="{{$evento_detalle[0]->precio_evento}}"></td>
        </tr>
      </table>
    </div>
  </div>

@if($evento[0]->condicion == 2)


  <div class="col-lg-6 col-md-6 col-sm-6">

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
