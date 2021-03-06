@extends('layouts.admin')
@section('contenido')

<script>
var cont = 0;
var cont_ = 0;
var debe_sum = 0;
var haber_sum = 0;

  function calc(i, z){

    if(z == 1){
        sv = "#precio_bruto_" + i;
        v1 = $(sv).val();
        sv = "#cant_usada_" + i;
        v2 = $(sv).val();
        sv = "costo_ingr_" + i;
        document.getElementById(sv).value = v1 * v2;
    }
    total = 0;
    total_2 = 0;
    aux = 0;

    for(a = 0; a < {{$ingredientes_num}}; a++){
      sv = "#costo_ingr_" + a;
      aux = $(sv).val();
      total = parseInt(aux) + total;
    }
    for(e = 0; e < {{$extras_num}}; e++){
      sv = "#costo_ext_" + e;
      aux = $(sv).val();
      total_2 = parseInt(aux) + total_2;
    }
    document.getElementById('total_extra').value = parseInt(total_2);
    document.getElementById('costo_ingr_total').value = parseInt(total);
    document.getElementById('costo_total_evento').value = parseInt(total) +  parseInt(total_2) + {{$eventos_detalle[0]->pago_cocineros}};
    document.getElementById('IVA_ingredientes').value = parseInt(total * 0.19);
    document.getElementById('IVA_ajustado').value = parseInt({{$eventos_detalle[0]->precio_evento*0.19}} - total * 0.19);
    document.getElementById('Utilidad_final').value = parseInt({{$eventos_detalle[0]->precio_evento}} - {{$eventos_detalle[0]->precio_evento*0.19}} + total * 0.19 - total - {{$eventos_detalle[0]->pago_cocineros}} - total_2);

    let pu = ($('#Utilidad_final').val()/{{$eventos_detalle[0]->precio_evento}})*100;
    document.getElementById('porcentaje_utilidad').value = pu.toFixed(2);
  }
</script>

<input name="id_evento_" value="{{$id}}" class="hidden"/>
<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Costos de la cotización</h3>
  </div>
</div>
<div class="row">
  <div class="col-md-12 col-lg-12">
    <h4>Ingredientes</h4>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color:#7DCEA0">
              <th>Ingrediente</th>
              <th>Cantidad sugerida</th>
              <th>Unidades</th>
              <th>Precio bruto registrado</th>
              <th>Costo</th>
            </thead>
            <?php
              $total = 0;
              $i = 0;
            ?>
            @foreach($ingredientes as $ingr)
            <tr>
              <td>{{$ingr->nombre}}</td>
              @if($ingr->unidad == 'gramos')
              <th><input value="{{round($ingr->sum/1000,1)}}" class="form-control" name="cantidad_usada[]" id="cant_usada_{{$i}}" onkeyup="calc({{$i}}, 1)"/></th>
              @else
              <th>
                <input value="{{round($ingr->sum)}}" class="form-control" name="cantidad_usada[]" id="cant_usada_{{$i}}" onkeyup="calc({{$i}}, 1)"/>
              </th>
              @endif
              <td>
                {{$ingr->uni_inv}}
                <input value="{{$ingr->uni_inv}}" class="hidden" name="unidades[]"/>
              </td>

              <th>
                <input class="form-control hidden" name="id_ingr[]" value="{{$ingr->id_ingr}}">
                <input class="form-control" value="{{$ingr->precio_bruto}}" onkeyup="calc({{$i}}, 1)" id="precio_bruto_{{$i}}" name="precio_bruto_ingr[]">
              </th>


              @if($ingr->uni_inv == 'Kg')
              <?php $total += $ingr->sum/1000 * $ingr->precio_bruto;
              ?>
              <th><input class="form-control" readonly="readonly" value="{{round($ingr->sum/1000, 1) * $ingr->precio_bruto}}" id="costo_ingr_{{$i}}" name="costo_ingr[]"></th>
              <th><input class="form-control hidden" readonly="readonly" value="{{$ingr->sum/1000}}" id="sum_{{$i}}" ></th>
              @endif
              @if($ingr->uni_inv == 'unidad' || $ingr->uni_inv == 'lámina')
              <?php $total += round($ingr->sum) * $ingr->precio_bruto;
              ?>
              <th><input class="form-control" readonly="readonly" value="{{round($ingr->sum) * $ingr->precio_bruto}}" id="costo_ingr_{{$i}}" name="costo_ingr[]"></th>
              <th><input class="form-control hidden" readonly="readonly" value="{{round($ingr->sum)}}" id="sum_{{$i}}" ></th>
              @endif
              <td>

            </tr>
            <?php $i++ ?>
            @endforeach
          </table>
        </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 col-lg-12">
      <h4>Extras</h4>
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
                <input class="form-control" value="{{$ext->precio}}" onkeyup="calc(0,0)" id="costo_ext_{{$i}}" name="costo_ext[]">
                <?php $total_ext += $ext->precio;
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
      <input class="form-control" id="costo_ingr_total" readonly="readonly" name="costo_ingr_total" value="{{$total  + $total_ingr_ext}}">
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
      <input class="form-control" readonly="readonly" id="costo_total_evento" name="costo_final_evento" value="{{$total + $total_ext + $total_ingr_ext + $eventos_detalle[0]->pago_cocineros}}">
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
    <label for="IVA del evento">IVA del evento</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" value="{{round($eventos_detalle[0]->precio_evento - $eventos_detalle[0]->precio_evento / 1.19)}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="Iva ingredientes">IVA ingredientes</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" name="IVA_ingredientes" id="IVA_ingredientes" value="{{round(($total - $total / 1.19) + ($total_ingr_ext - $total_ingr_ext / 1.19))}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="IVA del evento">IVA ajustado</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" id="IVA_ajustado" value="{{round($eventos_detalle[0]->precio_evento - $eventos_detalle[0]->precio_evento / 1.19 - (($total - $total / 1.19) + ($total_ingr_ext - $total_ingr_ext / 1.19)))}}">
    </div>
  </div>
  <?php
      $iva_ = ($eventos_detalle[0]->precio_evento - $eventos_detalle[0]->precio_evento / 1.19) - (($total - $total / 1.19) + ($total_ingr_ext - $total_ingr_ext / 1.19));
   ?>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="Utilidad final">Utilidad final</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" name="Utilidad_final" id="Utilidad_final" value="{{round($eventos_detalle[0]->precio_evento - $iva_ - $total - $total_ingr_ext - $eventos_detalle[0]->gasto_extra - $eventos_detalle[0]->pago_cocineros)}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <label for="IVA del evento">Porcentaje de ganancia</label>
    <div class="input-group">
      <span class="input-group-addon">%</span>
      <input class="form-control" readonly="readonly" id="porcentaje_utilidad" value="{{round(100*($eventos_detalle[0]->precio_evento - $eventos_detalle[0]->precio_evento * 0.19 + $total*0.19 - $total + $total_ingr_ext*0.19 - $eventos_detalle[0]->gasto_extra - $eventos_detalle[0]->pago_cocineros)/$eventos_detalle[0]->precio_evento)}}">
    </div>
  </div>
</div>
<hr></hr>

@endsection
