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
</script>

{!!Form::open(array('url'=>'carritos/ingresos','method'=>'POST','autocomplete'=>'off'))!!}
{{Form::Token()}}

<div class="hidden">
  <input name="id_evento_" value="{{$id}}" hidden>
  <input name="id_cliente_" value="{{$cliente->id}}" hidden>
</div>

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Generar ingreso del evento {{$id}} </h3>
    <hr></hr>
  </div>
</div>
<h4>Datos cliente ({{$cliente->id}})</h4>
<div class="row">
  <div class="col-lg-3 col-md-3 col-sm-3">
    <label>Nombre cliente</label>
    <input name="nombre_cliente" class="form-control" readonly="readonly" value="{{$cliente->nombre}} {{$cliente->apellido}}" type="text">
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3">
    <label>Rut cliente</label>
    <input name="rut_cliente" class="form-control" readonly="readonly" value="{{$cliente->rut}}" type="text">
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3">
    <label>Teléfono</label>
    <input name="telefono_cliente" class="form-control" readonly="readonly" value="{{$cliente->contacto}}" type="text">
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3">
    <label>Email</label>
    <input name="email_cliente" class="form-control" readonly="readonly" value="{{$cliente->mail}}" type="text">
  </div>
</div>
<hr></hr>
<h4>Datos del documento</h4>

<div class="row">
  <div class="col-lg-3 col-md-3">
    <label>Tipo de documento </label>
    <select class="form-control" name="tipo_doc" required>
      <option>Boleta</option>
      <option>Factura de venta electrónica</option>
      <option>Factura de venta</option>
      <option>Factura exenta</option>
    </select>
  </div>
  <div class="col-lg-3 col-md-3">
    <label>Número de documento</label>
    <input name="num_doc" class="form-control" type="number" required>
  </div>
  <div class="col-lg-3 col-md-3">
    <label>Fecha del documento</label>
    <input name="fecha_doc" class="form-control" type="date" required>
  </div>
</div>
<hr></hr>
<div class="row">
  <div class="col-lg-3 col-md-3">
    <label>Valor neto</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input name="valor_neto" class="form-control" readonly="readonly" type="number" value="{{$eventos_detalle[0]->precio_evento - $eventos_detalle[0]->iva_por_pagar}}">
    </div>
  </div>
  <div class="col-lg-3 col-md-3">
    <label>Iva (19%)</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input name="valor_iva" class="form-control" readonly="readonly" type="number" value="{{$eventos_detalle[0]->iva_por_pagar}}">
    </div>
  </div>
  <div class="col-lg-3 col-md-3">
    <label>Valor total</label>
    <div class="input-group">
      <span class="input-group-addon">$</span>
      <input name="valor_total" id="total_final" readonly="readonly  " class="form-control" type="number" value="{{$eventos_detalle[0]->precio_evento}}">
    </div>
  </div>
</div>
<hr></hr>
<h4>Ingreso</h4>
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
@include('carritos.ingresos.tabla_costos')
<div class="row" id="save" hidden>
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <div class="form-group">
      <a href=""><button class="btn btn-primary" type="submit">Aprobar</button></a>
    </div>
  </div>
</div>

@endsection
