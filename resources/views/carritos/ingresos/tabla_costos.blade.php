<script>
var cont_c = 0;
var cont__c = 0;
var debe_sum_c = 0;
var haber_sum_c = 0;

function addCuentas_c(){

  cuenta=$("#id_item_c option:selected").val();
  nom=$("#id_item_c option:selected").text();
  debe=$("#debe_c").val();
  haber=$("#haber_c").val();
  glosa=$("#glosa_c").val();

  if(cuenta != ""){

    var fila = '<tr class="selected" id="fila_c'+cont_c+'"><td><button type="button" class="btn btn-warning" onClick="eliminar_c('+cont_c+')">X</button></td><td><input hidden name="id_cuenta_c[]" value="'+cuenta+'"> <input class="form-control" disabled value="'+nom+'"></td><td><input class="form-control" type="number" id="id_debe_cuenta_c'+cont_c+'" name="debe_cuenta_c[]" readonly="readonly" value="'+debe+'"></td><td><input class="form-control" type="number" id="id_haber_cuenta_c'+cont_c+'" readonly="readonly" name="haber_cuenta_c[]" value="'+haber+'"></td><td><input class="form-control" name="glosa_cuenta_c[]" value="'+glosa+'"></td></tr>';

    vaciar_c();

    $("#detalles_c").append(fila);

    cont_c++;
    cont__c++;
    //mostrar_buttons();

    if(debe != ''){
      debe_sum_c = parseFloat(debe) + parseFloat(debe_sum_c);
    }
    if(haber != ''){
      haber_sum_c = parseFloat(haber) + parseFloat(haber_sum_c);
    }
    document.getElementById('total_debe_c').value = parseFloat(debe_sum_c);
    document.getElementById('total_haber_c').value = parseFloat(haber_sum_c);
    //comprobar_c();

  }else{
    alert("Faltan campos por rellenar");
  }
}
function comprobar_c(){
  if(debe_sum_c == haber_sum_c){
    $("#total_debe_c").css("background-color", "#CEF6CE");
    $("#total_haber_c").css("background-color", "#CEF6CE");

  }else{
    $("#total_debe_c").css("background-color", "#F6CED8");
    $("#total_haber_c").css("background-color", "#F6CED8");
  }
  if($("#total_final_c").val() == $("#total_debe_c").val() && $("#total_final_c").val() == $("#total_haber_c").val()) {
    $("#total_final_c").css("background-color", "#CEF6CE");
    if($("#total_final").val() == $("#total_debe").val() && $("#total_final").val() == $("#total_haber").val()) {
      $("#total_final").css("background-color", "#CEF6CE");
      mostrar_buttons();
    }else{
      $("#total_final").css("background-color", "#F6CED8");
      ocultar_buttons();

    }
  }else{
    $("#total_final_c").css("background-color", "#F6CED8");
    ocultar_buttons();
  }

}
function eliminar_c(index){

  sv = "#id_debe_cuenta_c" + index;
  if($(sv).val() != ''){
    debe_sum_c = parseFloat(debe_sum_c) - $(sv).val();
  }

  sv = "#id_haber_cuenta_c" + index;
  if($(sv).val() != ''){
    haber_sum_c = parseFloat(haber_sum_c) - $(sv).val();
  }
  document.getElementById('total_debe_c').value = parseFloat(debe_sum_c);
  document.getElementById('total_haber_c').value = parseFloat(haber_sum_c);
  //comprobar();
    sv = "#fila_c" + index;

    $(sv).remove();
    cont__c--;
    if(cont__c == 0){
      ocultar_buttons_c();
    }
    vaciar_c();
}
function vaciar_c(){
  $("#debe_c").val("");
  $("#haber_c").val("");
  $("#glosa_c").val("");
}
function mostrar_buttons_c(){
    $("#save_c").show();
}
function ocultar_buttons_c(){
    $("#save_c").hide();
}
</script>
<hr></hr>
<h4>Costos del evento</h4>
<div class="row">
  <div class="col-lg-3 col-md-3 col-sm-12">
    <label>Costo total del evento</label>
    <input id="total_final_c" class="form-control" readonly="readonly" value="{{$eventos_detalle[0]->costo_final}}">
  </div>
  <div class="col-lg-3 col-md-3 col-sm-12">
    <label>Costo cocineros</label>
    <input name="costo_total" class="form-control" readonly="readonly" value="{{$eventos_detalle[0]->pago_cocineros}}" type="text">
  </div>
  <div class="col-lg-3 col-md-3 col-sm-12">
    <label>Costo ingredientes</label>
    <input name="costo_total" class="form-control" readonly="readonly" value="{{$eventos_detalle[0]->total_ingredientes}}" type="text">
  </div>
  <div class="col-lg-3 col-md-3 col-sm-12">
    <label>Costo extras</label>
    <input name="costo_total" class="form-control" readonly="readonly" value="{{$eventos_detalle[0]->gasto_extra}}" type="text">
  </div>
</div>
<hr></hr>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#ABEBC6">
          <th>Ingredientes</th>
          <th>Cantidad</th>
          <th>Costo total</th>
        </thead>
        <?php
        $sum = 0;
        ?>

        @foreach($eci as $ingr)
        @if($ingr->inventareable == 1)
        <tr>
          <td>{{$ingr->nombre}}</td>
          <td>{{$ingr->cantidad}} {{$ingr->unidad}}</td>
          <th>$ {{$ingr->costo_total}}</th>
        </tr>
        @else
         <?php $sum += $ingr->costo_total?>
        @endif
        @endforeach
        <tr>
          <th>Otros ingredientes no inventareables</th>
          <td></td>
          <th>$ {{$sum}}</th>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#ABEBC6">
          <th>Extras</th>
          <th>Costo</th>
        </thead>

        @foreach($extras as $ext)

        <tr>
          <td>{{$ete->valor}}</td>
          <td>{{$ete->costo}}</td>
        </tr>

        @endforeach
      </table>
    </div>
  </div>
</div>
<hr></hr>
<h4>Costos</h4>
<div class="row">
  <div class="panel panel-primary">
    <div class="panel-body">
      <div class="col-lg-3 col-sm-3 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Cuentas</label>
          <select name="id_item_c" class="form-control selectpicker" id="id_item_c" data-live-search="true">
            @foreach($cuentas as $cuenta)
              <option value="{{$cuenta->id}}">{{$cuenta->nombre_cuenta}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Debe</label>
          <input type="number"  name="debe_c" id="debe_c" class="form-control">
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Haber</label>
          <input type="text" name="haber_c" id="haber_c" class="form-control" >
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Glosa</label>
          <input type="text" name="glosa_c" id="glosa_c" class="form-control">
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
        <div class="form-group">
          <label>Agregar </label>
          <button type="button"  onClick="addCuentas_c()" class="btn btn-primary">+</button>
        </div>
      </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <table id="detalles_c" class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#F5BCA9">
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
                  <input id="total_debe_c" class="form-control" required readonly="readonly"></input>
                </div>
            </th>
            <th>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input id="total_haber_c" class="form-control" required readonly="readonly"></input>
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
