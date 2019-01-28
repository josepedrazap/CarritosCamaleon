@extends ('layouts.admin')
@section('contenido')

<div class="row ">
  <div class="col-lg-12 col-md-12">
        <h4>Datos del evento {{$id}}</h4>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="nombre_cliente">Nombre cliente</label>
      <h5>{{$evento[0]->nombre}} {{$evento[0]->apellido}}</h5>
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="direccion_cliente">Dirección</label>
      <h5>{{$evento[0]->direccion}}</h5>
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="fecha_cliente">Fecha</label>
      <h5>{{$evento[0]->fecha_hora}}</h5>
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="telefono_cliente">Teléfono</label>
        <h5>{{$evento[0]->contacto}}</h5>
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="email_cliente">Estado del evento</label>
      @if($evento[0]->condicion == 1)
      <h5 style="color:orange">Por realizar</h5>
      @elseif($evento[0]->condicion == 2)
      <h5 style="color:green">Realizado</h5>
      @elseif($evento[0]->condicion == 3)
      <h5 style="color:red">Cancelado</h5>
      @endif
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="email_cliente">Estado del pago</label>
      @if($evento[0]->estado_pago == 2)
      <h5 style="color:green">Pagado</h5>
      @elseif($evento[0]->estado_pago == 1)
      <h5 style="color:orange">Pagado parcialmente</h5>
      @elseif($evento[0]->estado_pago == 0)
      <h5 style="color:red">No pagado</h5>
      @endif
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
          <th>Precio neto por unidad</th>
          <th>Precio bruto por unidad</th>
          <th>IVA total a pagar </th>
          <th>Total</th>
        </thead>

        @foreach($productos as $prod)
        <tr>
          <td>{{$prod->nombre}}</td>
          <td>{{$prod->cantidad}}</td>
          <td>${{$prod->precio_neto_unidad}}</td>
          <td>${{$prod->precio_neto_unidad * 1.19}}</td>
          <td>${{$prod->precio_neto_unidad * 0.19 * $prod->cantidad}}</td>
          <td>${{$prod->precio_neto_unidad * 1.19 * $prod->cantidad}}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
  @if(1==1)
    <div class="col-lg-12 col-md-12 col-sm-12">
      <hr></hr>
      <h4>Ingredientes totales del evento</h4>

      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#7DCEA0">
            <th>Ingredientes</th>
            <th>Cantidad sugerida</th>
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
          </tr>
          @endforeach
          @foreach($ingredientes_extras as $ingr)
          @if($ingr->aux == 0)
          <tr>
            <td>{{$ingr->nombre}}</td>
            @if($ingr->unidad == "gramos" || $ingr->unidad == "unidad")
            @if($ingr->unidad == "gramos")
            <td>{{$ingr->sum / 1000}} kg</td>
            @endif
            @if($ingr->unidad == "unidad")
            <td>{{round($ingr->cantidad * $ingr->porcion_unitaria)}} unidades</td>
            @endif
            @else
            <td>{{$ingr->cantidad * $ingr->porcion_unitaria}} unidades</td>
            @endif
          </tr>
          @endif
          @endforeach
        </table>
      </div>
    </div>
    @endif

  @if(count($extras) != 0 || count($otros) != 0)
  <div class="col-lg-12 col-md-12 col-sm-12">
    <hr></hr>
    <h4>Extras del evento</h4>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#F9E79F">
          <th class="col-lg-2 col-md-2 col-sm-2">Extras</th>
          <th>Cantidad</th>
          <th class="col-lg-2 col-md-2 col-sm-2">Costo neto empresa</th>
          <th class="col-lg-2 col-md-2 col-sm-2">Precio neto </th>
        </thead>
        @foreach($extras as $ext)
        <tr>
          <td>{{$ext->valor}}</td>
          <td>{{$ext->cantidad}}</td>
          <td>$ {{$ext->costo_neto_unidad}}</td>
          <td>$ {{$ext->precio_neto_unidad}}</td>
        </tr>
        @endforeach
        @foreach($otros as $ot)
        <tr>
          <td>{{$ot->nombre}} (nuevo)</td>
          <td>{{$ot->cantidad}}</td>
          <td>$ {{$ot->costo_neto_unidad}}</td>
          <td>$ {{$ot->precio_neto_unidad}}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
  @endif

@if(1!=1)
<!-- <div class="col-lg-6 col-md-6 col-sm-12">
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
</div> -->
@endif
</div>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <hr></hr>
    <h4>Trabajadores del evento</h4>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#F1948A">
          <th>Trabajo</th>
          <th>Nombre</th>
          <th>Estado del pago</th>
          <th>Pago líquido</th>
          <th>Retención</th>
          <th>Total</th>
        </thead>
        @foreach($trabajadores as $tra)
        <tr>
          <td>{{$tra->trabajo}}</td>
          <td>{{$tra->nombre}} {{$tra->apellido}}</td>
          @if($tra->estado == 1)
          <td>Pendiente</td>
          @elseif($tra->estado == 2)
          <td>Pagado</td>
          @endif
          <td>${{$tra->monto}}</td>
          <td>${{round($tra->monto / 0.9 - $tra->monto)}}</td>
          <td>${{round($tra->monto / 0.9)}}</td>
        </tr>
        @endforeach
      </table>
  </div>
</div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <hr></hr>
    <h4>Totales del evento</h4>

  <table class="table table-striped table-bordered table-condensed table-hover">
    <thead style="background-color:#D2B4DE">
      <th>Costo parcial neto</th>
      <th>Costo parcial bruto</th>
      <th>Ganancia neta</th>
      <th>Ganancia bruta</th>
      <th>(%) estimado de ganancia neto</th>
      <th>Total Neto</th>
      <th>Iva</th>
      <th>Monto a cobrar evento (Bruto)</th>
    </thead>
    <tr>
      <td>${{$simulacion[0]->costo_parcial_neto}}</td>
      <td>${{$simulacion[0]->costo_parcial_bruto}}</td>
      <td>${{$simulacion[0]->ganancia_neta}}</td>
      <td>${{$simulacion[0]->ganancia_bruta}}</td>
      <td>%{{$simulacion[0]->porcentaje_ganacia}}</td>
      <td>${{$simulacion[0]->total_final_neto}}</td>
      <td>${{$simulacion[0]->total_final_iva}}</td>
      <td>${{$simulacion[0]->total_final_bruto}}</td>
    </tr>
  </table>
</div>
</div>
<hr></hr>
<div class="row">
  <div class="col-md-6">
    <a href="/carritos/pdf/despacho_checklist?id={{$id}}"><input class="btn btn-warning" type="button" value="Guía de producción"/></a>
    <a href="/estado_evento?id={{$id}}"><input class="btn btn-primary" type="button" value="Estado del evento"/></a>
  </div>
</div>


{!!Form::close()!!}
@endsection
