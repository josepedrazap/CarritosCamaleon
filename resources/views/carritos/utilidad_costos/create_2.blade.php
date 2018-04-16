@extends('layouts.admin')
@section('contenido')

{!!Form::open(array('url'=>'carritos/utilidad_costos','method'=>'POST','autocomplete'=>'off'))!!}
{{Form::Token()}}


<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h4>Costos del evento id <input name="id_evento_" value="{{$id}}"></h4>
    <hr></hr>
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-lg-12">
    <h3>Ingredientes</h3>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color:#7DCEA0">
              <th>Ingrediente</th>
              <th>Cantidad usada</th>
              <th>Unidades</th>
              <th>Costo empresa</th>
            </thead>
            @foreach($ingredientes_tmp as $ingr)
            <tr>
              <th>{{$ingr->nombre}}</th>
              <th>{{$ingr->cantidad}}</th>
              <th>{{$ingr->unidad}}</th>
              <th>{{$ingr->costo_total}}</th>
            </tr>
            @endforeach
          </table>
        </div>
  </div>


  <div class="col-md-12 col-lg-12">
    <h3>Ingredientes extras</h3>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#7DCEA0">
          <th>Ingredientes</th>
          <th colspan="2">Cantidad usada</th>
          <th>Precio de venta</th>
          <th>Costo empresa</th>
        </thead>
        <?php
          $total_ingr_ext = 0;
          $i = 0;
        ?>
        @foreach($ingr_extras as $ext)
        <tr>
          <td>{{$ext->nombre}}</td>
          @if($ext->uni_porcion == 'gramos')
          <th><input value="{{round($ext->cantidad_total/1000,1)}}" readonly="readonly" name="cantidad_usada_ext[]" class="form-control" /></th>
          <th>Kg</th>
          @else
          <th>
            <input value="{{round($ext->cantidad_total)}}" readonly="readonly" name="cantidad_usada_ext[]" class="form-control" />
          </th>
          <th>{{$ext->uni_porcion}}</th>
          @endif
          <td>$ {{$ext->precio}}</td>
          <th>
            <input class="form-control hidden" name="id_ingr_ext[]" value="{{$ext->id}}">
            <input class="form-control" value="{{$ext->costo}}" readonly="readonly" id="costo_ingr_ext_{{$i}}" name="costo_ingr_ext[]">
            <?php $total_ingr_ext += $ext->costo;
            ?>
          </th>
        </tr>
        <?php $i++ ?>
        @endforeach
      </table>
    </div>
</div>

      <div class="col-md-12 col-lg-12">
        <h3>Extras</h3>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color:#7DCEA0">
              <th>Extra</th>
              <th>Precio de venta</th>
              <th>Costo</th>
            </thead>
            <?php
              $total_ext = 0;
              $i = 0;
            ?>
            @foreach($extras as $ext)
            <tr>
              <td>{{$ext->valor}}</td>
              <td>$ {{$ext->precio}}</td>
              <th>
                <input class="form-control hidden" name="id_ext[]" value="{{$ext->id_ete}}">
                <input class="form-control" value="{{$ext->costo}}" readonly="readonly" id="costo_ext_{{$i}}" name="costo_ext[]">
                <?php $total_ext += $ext->costo;
                ?>
              </th>
            </tr>
            <?php $i++ ?>
            @endforeach
          </table>
        </div>
  </div>
</div>

<hr></hr>
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="Costo en ingredientes">Costo en ingredientes</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" id="costo_ingr_total" readonly="readonly" name="costo_ingr_total" value="{{$eventos_detalle[0]->total_ingredientes}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="Costo en cocineros">Costo en cocineros</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" value="{{$eventos_detalle[0]->pago_cocineros}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="Costo en extras">Costo en extras</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" name="costo_extras" readonly="readonly" id="total_extra" value="{{$total_ext}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="Costo total del evento">Costo total del evento</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" id="costo_total_evento" name="costo_final_evento" value="{{$eventos_detalle[0]->costo_final}}">
    </div>
  </div>
</div>
<hr></hr>
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="Precio del evento">Precio del evento</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" value="{{$eventos_detalle[0]->precio_evento}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="IVA del evento">IVA ajustado</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" id="IVA_ajustado" value="{{$eventos_detalle[0]->iva_por_pagar - $eventos_detalle[0]->total_ingredientes_iva}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="Utilidad final">Utilidad final</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" name="Utilidad_final" id="Utilidad_final" value="{{$eventos_detalle[0]->utilidad_final}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="IVA del evento">Porcentaje de ganancia</label>
    <div class="input-group">
      <span class="input-group-addon">%</span>
      <input class="form-control" readonly="readonly" id="porcentaje_utilidad" value="{{round(100 * $eventos_detalle[0]->utilidad_final/$eventos_detalle[0]->precio_evento)}}">
    </div>
  </div>
</div>
<hr></hr>
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <div class="form-group">

    </div>
  </div>
</div>
{!!Form::close()!!}
@endsection
