@extends('layouts.admin')
@section('contenido')

<script>
var cont = 0;
var cont_ = 0;
var debe_sum = 0;
var haber_sum = 0;

function addCuentas(){

  cuenta=$("#id_item option:selected").val();
  nom=$("#id_item option:selected").text();
  debe=$("#debe").val();
  haber=$("#haber").val();
  glosa=$("#glosa").val();

  if(cuenta != ""){

    var fila = '<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onClick="eliminar('+cont+')">X</button></td><td><input hidden name="id_cuenta[]" value="'+cuenta+'"> <input class="form-control" disabled value="'+nom+'"></td><td><input class="form-control" type="number" id="id_debe_cuenta'+cont+'" name="debe_cuenta[]" readonly="readonly" value="'+debe+'"></td><td><input class="form-control" type="number" id="id_haber_cuenta'+cont+'" readonly="readonly" name="haber_cuenta[]" value="'+haber+'"></td><td><input class="form-control" name="glosa_cuenta[]" value="'+glosa+'"></td></tr>';

    vaciar();

    $("#detalles").append(fila);

    cont++;
    cont_++;
    //mostrar_buttons();

    if(debe != ''){
      debe_sum = parseFloat(debe) + parseFloat(debe_sum);
    }
    if(haber != ''){
      haber_sum = parseFloat(haber) + parseFloat(haber_sum);
    }
    document.getElementById('total_debe').value = parseFloat(debe_sum);
    document.getElementById('total_haber').value = parseFloat(haber_sum);
    comprobar();

  }else{
    alert("Faltan campos por rellenar");
  }
}
function comprobar(){
  if(debe_sum == haber_sum){
    $("#total_debe").css("background-color", "#CEF6CE");
    $("#total_haber").css("background-color", "#CEF6CE");

  }else{
    $("#total_debe").css("background-color", "#F6CED8");
    $("#total_haber").css("background-color", "#F6CED8");
  }
  if($("#total_final").val() == $("#total_debe").val() && $("#total_final").val() == $("#total_haber").val()) {
    $("#total_final").css("background-color", "#CEF6CE");
    mostrar_buttons();
  }else{
    $("#total_final").css("background-color", "#F6CED8");
    ocultar_buttons();
  }

}
function eliminar(index){

  sv = "#id_debe_cuenta" + index;
  if($(sv).val() != ''){
    debe_sum = parseFloat(debe_sum) - $(sv).val();
  }

  sv = "#id_haber_cuenta" + index;
  if($(sv).val() != ''){
    haber_sum = parseFloat(haber_sum) - $(sv).val();
  }
  document.getElementById('total_debe').value = parseFloat(debe_sum);
  document.getElementById('total_haber').value = parseFloat(haber_sum);
  //comprobar();
    sv = "#fila" + index;

    $(sv).remove();
    cont_--;
    if(cont_ == 0){
      ocultar_buttons();
    }
    vaciar();
}
function vaciar(){
  $("#debe").val("");
  $("#haber").val("");
  $("#glosa").val("");
}
function mostrar_buttons(){
    $("#save").show();
}
function ocultar_buttons(){
    $("#save").hide();
}
  function calc(i, z){

    if(z == 1){
        sv = "#precio_bruto_" + i;
        v1 = $(sv).val();
        sv = "#sum_" + i;
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

{!!Form::open(array('url'=>'carritos/utilidad_costos','method'=>'POST','autocomplete'=>'off'))!!}
{{Form::Token()}}

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Generar ingreso del evento {{$id}} <input name="id_evento_" value="{{$id}}" hidden></h3>
    <hr></hr>
  </div>
</div>

<div class="row">
  <div class="col-lg-3 col-md-3">
    <label>Tipo de documento </label>
    <select class="form-control" name="tipo_doc">
      <option>Factura de compra electrónica</option>
      <option>Factura de compra</option>
      <option>Nota de crédito de compra</option>
      <option>Nota de crédito de compra electrónica</option>
      <option>Nota de débito de compra</option>
      <option>Nota de débito de compra electrónica</option>
      <option>Factura exenta</option>
    </select>
  </div>
  <div class="col-lg-3 col-md-3">
    <label>Número de documento</label>
    <input name="num_doc" class="form-control" type="number">
  </div>
  <div class="col-lg-3 col-md-3">
    <label>Fecha del documento</label>
    <input name="fecha_doc" class="form-control" type="date">
  </div>
</div>
<hr></hr>
<div class="row">
  <div class="col-lg-3 col-md-3">
    <label>Valor neto</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input name="valor_neto" class="form-control" type="number" value="{{$eventos_detalle[0]->precio_evento - $eventos_detalle[0]->iva_por_pagar}}">
    </div>
  </div>
  <div class="col-lg-3 col-md-3">
    <label>Iva (19%)</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input name="valor_iva" class="form-control" type="number" value="{{$eventos_detalle[0]->iva_por_pagar}}">
    </div>
  </div>
  <div class="col-lg-3 col-md-3">
    <label>Valor total</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input name="valor_exento" id="total_final" class="form-control" type="number" value="{{$eventos_detalle[0]->precio_evento}}">
    </div>
  </div>
</div>
<hr></hr>


<div class="row">
  <div class="panel panel-primary">
    <div class="panel-body">
      <div class="col-lg-3 col-sm-3 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Cuentas</label>
          <select name="id_item" class="form-control selectpicker" id="id_item" data-live-search="true">
            @foreach($cuentas as $cuenta)
              <option value="{{$cuenta->id}}">{{$cuenta->nombre_cuenta}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Debe</label>
          <input type="number"  name="debe" id="debe" class="form-control">
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Haber</label>
          <input type="text" name="haber" id="haber" class="form-control" >
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Glosa</label>
          <input type="text" name="glosa" id="glosa" class="form-control">
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Agregar </label>
          <button type="button"  onClick="addCuentas()" class="btn btn-primary">+</button>
        </div>
      </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#A9D0F5">
            <th>Opciones</th>
            <th>Nombre cuenta</th>
            <th>Debe</th>
            <th>Haber</th>
            <th>Glosa</th>
          </thead>
          <tfoot>
            <th></th>
            <th></th>
            <th>
                <div class="input-group">
                  <span class="input-group-addon">$</span>
                  <input id="total_debe" class="form-control" required readonly="readonly"></input>
                </div>
            </th>
            <th>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input id="total_haber" class="form-control" required readonly="readonly"></input>
              </div></th>
            <th></th>
          </tfoot>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<hr></hr>
<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Costos del evento</h3>
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
              <td>{{round($ingr->sum/1000,1)}} Kg</td>
              @else
              <td>{{round($ingr->sum)}} {{$ingr->unidad}}</td>
              @endif
              <td>{{$ingr->uni_inv}}</td>

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
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <label for="Costo en ingredientes">Costo en ingredientes</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" id="costo_ingr_total" readonly="readonly" name="costo_ingr_total" value="{{$total}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <label for="Costo en cocineros">Costo en cocineros</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" value="{{$eventos_detalle[0]->pago_cocineros}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <label for="Costo en extras">Costo en extras</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" name="costo_extras" readonly="readonly" id="total_extra" value="{{$total_ext}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <label for="Costo total del evento">Costo total del evento</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" id="costo_total_evento" name="costo_final_evento" value="{{$total + $total_ext + $eventos_detalle[0]->pago_cocineros}}">
    </div>
  </div>
</div>
<hr></hr>
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <label for="Precio del evento">Precio del evento</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" value="{{$eventos_detalle[0]->precio_evento}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <label for="Iva ingredientes">IVA ingredientes</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" name="IVA_ingredientes" id="IVA_ingredientes" value="{{$total * 0.19}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <label for="IVA del evento">IVA del evento</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" value="{{$eventos_detalle[0]->precio_evento * 0.19}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <label for="IVA del evento">IVA ajustado</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" id="IVA_ajustado" value="{{$eventos_detalle[0]->precio_evento * 0.19 - $total*0.19}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <label for="Utilidad final">Utilidad final</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" name="Utilidad_final" id="Utilidad_final" value="{{$eventos_detalle[0]->precio_evento - $eventos_detalle[0]->precio_evento * 0.19 + $total*0.19 - $total - $eventos_detalle[0]->gasto_extra - $eventos_detalle[0]->pago_cocineros}}">
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <label for="IVA del evento">Porcentaje de ganancia</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input class="form-control" readonly="readonly" id="porcentaje_utilidad" value="{{100*($eventos_detalle[0]->precio_evento - $eventos_detalle[0]->precio_evento * 0.19 + $total*0.19 - $total - $eventos_detalle[0]->gasto_extra - $eventos_detalle[0]->pago_cocineros)/$eventos_detalle[0]->precio_evento}}">
    </div>
  </div>
</div>
<hr></hr>
<div class="row" id="save" hidden>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
    <div class="form-group">
      <a href=""><button class="btn btn-primary" type="submit">Aprobar</button></a>
    </div>
  </div>
</div>
{!!Form::close()!!}
@endsection
