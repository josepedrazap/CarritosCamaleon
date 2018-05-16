@extends ('layouts.admin')
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
    mostrar_buttons();

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
  comprobar();
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

{!!Form::open(array('url'=>'carritos/ajustes','method'=>'POST','autocomplete'=>'off'))!!}
{{Form::Token()}}

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Ingresar ajuste número de comprobante {{$serie->id + 1501}}</h3>
      <label for="fecha">Fecha de ingreso</label>
      <input name="fecha_ingreso" type="date" class="form-control" required></input>
    <hr/>
    @if(count($errors)>0)
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
      </ul>
    </div>
    @endif
  </div>
</div>



    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <h4>Datos del ajuste </h4>
      </div>
    </div>

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
    <div class="row">

        <input name="_token value={{csrf_token()}}" type="hidden"></input>
        <a href=""><button class="btn btn-primary" type="submit">Ingresar compra</button></a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>

  </div>

  {!!Form::close()!!}


@endsection
