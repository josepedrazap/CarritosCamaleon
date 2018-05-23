@extends ('layouts.admin')
@section('contenido')
<script>
arr = [];
aprobado = 1;
function axios_verificar_num(){
  let me = this;
  var numero = $("#numero_comprobante").val();
  var url = '/axios/prueba_numero_comprobante?serie_comprobante=' + numero;

          axios.get(url, {

          }).then(function (response) {
            var respuesta = response.data;
            me.arr = respuesta;
            console.log(me.arr);
            if(me.arr == 0){
              console.log('El numero de comprobante esta disponible');
              $("#numero_comprobante").css("background-color", "#74DF00");
              aprobado = 1;
            }else{
              console.log('El numero de comprobante esta ocupado');
              $("#numero_comprobante").css("background-color", "#FF4000");
              aprobado = 0;
            }
            comprobar();
          })
          .catch(function (error) {
            console.log(error);
          });

}
  function iva_calc(){
    if($("#tipo option:selected").text() != 'Factura exenta'){
      v1 = $("#monto").val();
      document.getElementById('total_final').value = parseFloat(v1) + parseFloat(v1*0.19);
      document.getElementById('total_iva').value = parseFloat(v1 * 0.19);
    }else{
      v1 = $("#monto").val();
      document.getElementById('total_final').value = parseFloat(v1);
      document.getElementById('total_iva').value = 0;
    }
  }
  var cont = {{$cont}};
  var cont_ = {{$cont}};
  var debe_sum = {{$total_debe}};
  var haber_sum = {{$total_haber}};


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
  if($("#total_final").val() == $("#total_debe").val() && $("#total_final").val() == $("#total_haber").val() && aprobado == 1) {
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

{!!Form::open(array('url'=>'carritos/compras/editar','method'=>'GET','autocomplete'=>'off'))!!}
{{Form::Token()}}

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Ver / Editar compra </h3>
      <label for="fecha">Número de comprobante</label>
      <input value="{{$id}}" name="id_documento" class="hidden"></input>
      <input type="number" class="form-control" value="{{$doc[0]->numero_comprobante}}" name="numero_comprobante" id="numero_comprobante" onkeyup="axios_verificar_num()" placeholder="Número comprobante">      <label for="fecha">Fecha de ingreso</label>
      <input name="fecha_ingreso" value="{{$doc[0]->fecha_ingreso}}" type="date" class="form-control" required></input>
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
        <h4>Datos de la compra </h4>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
          <label for="tipo">Proveedor</label>
          <div class="form-group">
            <select class="form-control selectpicker" name="id_proveedor" data-live-search="true" required >
              @foreach($prov as $p)

              @if($doc[0]->id_tercero == $p->id)
              <option selected value="{{$p->id}}">{{$p->rut}}</option>
              @else
              <option value="{{$p->id}}">{{$p->rut}}</option>
              @endif

              @endforeach
            </select>
          </div>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-3 col-xs-12">
          <div class="form-group">
            <label for="">Nuevo</label>
            <a href="/carritos/proveedores/create"><button type="button" class="btn btn-info">Nuevo</button></a>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <label for="fecha">Tipo de documento</label>
          <div class="form-group">
            <select name="tipo_documento" class="form-control" id="tipo" onchange="iva_calc()">
              <option>Factura de compra electrónica</option>
              <option>Factura de compra</option>
              <option>Nota de crédito de compra</option>
              <option>Nota de crédito de compra electrónica</option>
              <option>Nota de débito de compra</option>
              <option>Nota de débito de compra electrónica</option>
              <option>Factura exenta</option>
            </select>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <label for="fecha">Número de documento</label>
          <div class="form-group">
            <input name="numero_documento" value="{{$doc[0]->numero_documento}}" type="number" class="form-control" required></input>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <label for="fecha">Fecha documento</label>
          <div class="form-group">
            <input name="fecha_documento"value="{{$doc[0]->fecha_documento}}" type="date" class="form-control" required></input>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <label for="apellido">Monto neto</label>
          <div class="input-group">
            <span class="input-group-addon">$</span>
            <input type="text" class="form-control" value="{{$doc[0]->monto_neto}}" name="monto_neto" id="monto" onkeyup="iva_calc();" required>
          </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
          <label for="iva">Excento</label>
          <div class="input-group">
            <span class="input-group-addon">$</span>
            <input name="excento" value="{{$doc[0]->excento}}" class="form-control"  ></input>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
          <label for="iva">IVA</label>
          <div class="input-group">
            <span class="input-group-addon">$</span>
            <input id="total_iva" value="{{$doc[0]->iva}}" name="iva" class="form-control"  ></input>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
          <label for="iva">Otros impuestos</label>
          <div class="input-group">
            <span class="input-group-addon">$</span>
            <input name="otros_impuestos" value="{{$doc[0]->otros_impuestos}}" class="form-control" ></input>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <label for="total">Total</label>
          <div class="input-group">
            <span class="input-group-addon">$</span>
            <input id="total_final" name="total" value="{{$doc[0]->total}}" class="form-control" required ></input>
          </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <div class="input-group">
            <input class="hidden" class="form-control"></input>
          </div>
        </div>
      </div>
    </div>
    <hr></hr>
    <?php $i_ = 0; ?>
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

              <tbody>
                @foreach($cuentas_usadas as $ct)
                <tr class="selected" id="fila{{$i_}}">
                  <th>
                    <button type="button" class="btn btn-warning" onClick="eliminar({{$i_}})">X</button>
                  </th>
                  <th>
                    <input hidden name="id_cuenta[]" value="{{$ct->id_cuenta}}">
                    <input class="form-control" disabled value="{{$ct->nombre_cuenta}}">
                  </th>
                  <th>
                    <input class="form-control" type="number" id="id_debe_cuenta{{$i_}}" name="debe_cuenta[]" readonly="readonly" value="{{$ct->debe}}">
                  </th>
                  <th>
                    <input class="form-control" type="number" id="id_haber_cuenta{{$i_}}" readonly="readonly" name="haber_cuenta[]" value="{{$ct->haber}}">
                  </th>
                  <th>
                    <input class="form-control" name="glosa_cuenta[]" value="{{$ct->glosa}}">
                  </th>
                </tr>
                <?php
                    $i_ ++;
                 ?>
                @endforeach
              </tbody>
              <tfoot>
                <th></th>
                <th></th>
                <th>
                    <div class="input-group">
                      <span class="input-group-addon">$</span>
                      <input id="total_debe" class="form-control" value="{{$total_debe}}" required readonly="readonly"></input>
                    </div>
                </th>
                <th>
                  <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input id="total_haber" class="form-control" value="{{$total_haber}}" required readonly="readonly"></input>
                  </div>
                </th>
                <th></th>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group" id="save">
        <input name="_token value={{csrf_token()}}" type="hidden"></input>
        <a href=""><button class="btn btn-primary" type="submit">Guardar compra</button></a>
      </div>
  </div>

  {!!Form::close()!!}


@endsection
