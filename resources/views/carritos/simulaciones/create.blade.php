@extends ('layouts.admin')
@section('contenido')

<script>
a = 0;
e = 0;
var seguridad = 0;

function eval(i, v){
  if($("#_"+i).val() == ""){
    $("#_"+i).css("background-color", "#EAEDED");
  }
  if($("#"+i).val() - v < 0){
    $("#_"+i).css("background-color", "#F5B7B1");
  }else{
    $("#_"+i).css("background-color", "#A9CCE3");
  }
}
function calculo_total(valor){
      var total = 0;
      v1 = $("#total_sum").val();
      v3 = $('#total_ext').val();
      v4 = $('#total_ingr_ext').val();
      total = parseFloat(v1) + parseFloat(v3) + parseFloat(v4);

      document.getElementById('IVA').value = parseFloat(total * 0.19);
      document.getElementById('Display').value = total;
      document.getElementById('iva_total').value = total - total / 1.19;

    //  document.getElementById('total_sum').value = aux1;
      //document.getElementById('iva_sum').value = aux2;
}
function suma_cocinero(i){

  if(i == 1){
    total = parseFloat($("#pago_cocinero").val()) + parseInt($("#precio_real_" + i).val());
    document.getElementById('pago_cocinero').value = total;
    //calculo_total();
  }else{
    total = parseFloat($("#pago_cocinero").val()) - parseInt($("#precio_real_" + i).val());
    document.getElementById('pago_cocinero').value = total;
    //calculo_total();
  }
}
function eliminar_coc(){
  //total= total-subtotal[index];
  if(a > 0){
    sv = "#tra_" + a;
    $(sv).remove();
    suma_cocinero(0);
    a--;
    calculo_costos_total();

    seguridad = seguridad - 1;
  }
  mostrar_ocultar_buttons(seguridad);

}
function mostrar_ocultar_buttons(s){
    if(s > 0){
        $("#save").show();
    }else{
        $("#save").hide();
    }
}
function addCocinero(){
      a++;

        var div = document.createElement('div');
        div.setAttribute('class', 'form-inline');
            div.innerHTML ='<div id="tra_'+a+'" style="clear:both" class="trabajador_'+a+' col-lg-6 col-md-6 col-sm-6 col-xs-12"><select id="select'+a+'" class="form-control" name="trabajador[]"></select></div>';
            document.getElementById('trabajadores').appendChild(div);

            sv = "#select"+a;
            seguridad = seguridad + 1;
            llenar_select_trabajadores(a,sv);
            suma_cocinero(1);
            mostrar_ocultar_buttons(seguridad);
            calculo_costos_total();

}
function llenar_select_trabajadores(a, sv){
  @foreach($trabajadores as $tra)
    id = "{{$tra->id}}";
    aux = "{{$tra->nombre}} {{$tra->apellido}}";
    if({{$tra->maneja}} == 1){
        $(sv).append('<option value="'+id+'">(M) '+aux+'</option>');
    }else{
        $(sv).append('<option value="'+id+'">'+aux+'</option>');
    }

  @endforeach
}
function calculo_precio(t, i, p){

  if(p == 1){
    var total = 0;
    v1 = $("#precio_real_" + i).val();
    v2 = $("#cant_prod_" + i).val();
    total =  0.19 * parseInt(v1) * parseInt(v2);
    document.getElementById('IVA_por_pagar_'+i).value = total;
    document.getElementById('precio_liquido_'+i).value = Math.round( v1 * 1.19);
    total = parseInt(v1) * parseInt(v2);
    document.getElementById('total_'+i).value = parseInt(total*1.19);
    calculo_total_productos();
  }else{
    var total = 0;
    v1 = $("#precio_liquido_" + i).val();
    v2 = $("#cant_prod_" + i).val();
    v1 = v1 / 1.19;
    total =  0.19 * parseInt(v1) * parseInt(v2);
    document.getElementById('IVA_por_pagar_'+i).value = total;
    document.getElementById('precio_real_'+i).value =  Math.round(v1);
    total = parseInt(v1) * parseInt(v2);
    document.getElementById('total_'+i).value = parseInt(total*1.19);
    calculo_total_productos();
  }

}
function calculo_costos_total(){
  var total = 0;
  v1 = $("#total_costo_ingredientes").val();
  v2 = $("#total_costo_ext").val();
  v3 = $('#total_costo_ingr_ext').val();

  v4 = $('#monto_cocineros').val();
  total = parseFloat(v1) + parseFloat(v2) + parseFloat(v3) + parseFloat(v4)*parseFloat(a);
  document.getElementById('costo_parcial').value = total;

}
function calculo_precio_2(){
  var aux1 = 0;
  for(i=0; i<{{$num_ext}}; i++){
    aux1 += parseFloat($("#precio_ext_" + i).val());
  }
  var aux2 = 0;
  for(i=0; i<{{$num_ext}}; i++){
    aux2 += parseFloat($("#costo_ext_" + i).val());
  }
  document.getElementById('total_ext').value = aux1;
  document.getElementById('total_costo_ext').value = aux2;
  calculo_costos_total();
  calculo_total();
}
function calculo_precio_3(){
  var aux1 = 0;
  for(i=0; i<{{$num_ingr_ext}}; i++){
    aux1 += parseFloat($("#precio_ingr_ext_" + i).val());
  }
  var aux2 = 0;
  for(i=0; i<{{$num_ingr_ext}}; i++){
    aux2 += parseFloat($("#costo_ingr_ext_" + i).val());
  }
  document.getElementById('total_ingr_ext').value = aux1;
  document.getElementById('total_costo_ingr_ext').value = aux2;
  calculo_costos_total();
  calculo_total();
}
function calculo_total_productos(){
  var total = 0;
  var aux1 = 0;
  var aux2 = 0;
  for(i=0; i<{{$num_prod}}; i++){
    aux1 += parseFloat($("#total_" + i).val());
    aux2 += parseFloat($("#IVA_por_pagar_" + i).val());
  }
  document.getElementById('total_sum').value = aux1;
  document.getElementById('iva_sum').value = aux2;
  calculo_total();
}

</script>

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">


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

    {!!Form::open(array('url'=>'carritos/despacho','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::Token()}}

    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <h4>Datos de la simulación<input hidden name="id_simulacion_" value="{{$simulacion[0]->id}}"></h4>
        <hr></hr>
      </div>
    </div>

    <div class="row ">

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
          <label for="nombre_cliente">Nombre de la simulación</label>
          <h4>{{$simulacion[0]->nombre}}</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
          <label for="fecha_cliente">Fecha de la simulación</label>
          <h4>{{$simulacion[0]->fecha}}</h4>
        </div>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
          <label for="email_cliente">Descripción</label>
            <textarea class="form-control">{{$simulacion[0]->descripcion}}</textarea>
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
              <th>Precio sugerido unidad</th>
              <th>Precio neto unidad</th>
              <th>Precio bruto unidad</th>
              <th>IVA total por pagar</th>
              <th>Total</th>
            </thead>
            <tfoot>
              <th></th>

              <th></th>
              <th></th>
              <th></th>
              <th><h4><strong>Total:</strong></h4></th>
              <th><input class="form-control" name="iva_sum" id="iva_sum" placeholder="Iva total" readonly="readonly"></th>
              <th><input class="form-control" name="total_sum" id="total_sum" placeholder="Total" readonly="readonly"></th>
            </tfoot>

            <?php $i = 0 ?>

            @foreach($productos as $prod)
            <tr>
              <td>{{$prod->nombre}}</td>
              <td><input class="form-control" id="cant_prod_{{$i}}" value="{{$prod->cantidad}}" onkeyup="calculo_precio(this.value, {{$i}})"></td>
              <td>${{$prod->precio}}</td>
              <th><input name="precio_real[]" type="number" id="precio_real_{{$i}}" required onkeyup="calculo_precio(this.value, {{$i}}, 1)" class="form-control"></th>
              <th><input name="precio_liquido[]" type="number" id="precio_liquido_{{$i}}" onkeyup="calculo_precio(this.value, {{$i}}, 0)" class="form-control"></th>
              <th><input name="IVA_por_pagar[]" id="IVA_por_pagar_{{$i}}" readonly="readonly" class="form-control"></th>
              <th><input name="total_[]" id="total_{{$i}}" readonly="readonly" class="form-control"></th>
              <th><input name="id_etp[]" id="id_etp_{{$i}}" value="{{$prod->id_etp}}"  hidden></th>
            </tr>
            <?php $i++ ?>
            @endforeach
          </table>
        </div>
      </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
          <hr></hr>
          <h4>Ingredientes totales del evento</h4>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
              <thead style="background-color:#7DCEA0">
                <th>Ingredientes</th>
                <th>Cantidad sugerida</th>
                <th>Precio bruto unidad</th>
                <th>Costo total</th>
                <th>Stock en inventario</th>
              </thead>

              <?php
                $costo_total_tmp = 0;
               ?>
              @foreach($ingredientes as $ingr)
              <tr>
                <td>{{$ingr->nombre}}</td>
                @if($ingr->unidad == "gramos" || $ingr->unidad == "unidad" || $ingr->unidad == "lámina")
                @if($ingr->unidad == "gramos")
                <td>{{$ingr->sum / 1000}} kg</td>
                @endif
                @if($ingr->unidad == "unidad")
                <td>{{round($ingr->sum)}} unidades</td>
                @endif
                @if($ingr->unidad == "lámina")
                <td>{{round($ingr->sum)}} láminas</td>
                @endif
                @else
                <td>{{round($ingr->sum)}} unidades</td>
                @endif

                <td>$ {{$ingr->precio_bruto}} x {{$ingr->uni_inv}}</td>

                @if($ingr->unidad == "gramos" || $ingr->unidad == "unidad" || $ingr->unidad == "lámina")
                @if($ingr->unidad == "gramos")
                <td>$ {{$ingr->sum * $ingr->precio_bruto/ 1000}}</td>
                <?php
                  $costo_total_tmp += ($ingr->sum * $ingr->precio_bruto) / 1000;
                 ?>
                @endif
                @if($ingr->unidad == "unidad")
                <td>$ {{round($ingr->sum * $ingr->precio_bruto)}}</td>
                <?php
                  $costo_total_tmp += round($ingr->sum * $ingr->precio_bruto);
                 ?>
                @endif
                @if($ingr->unidad == "lámina")
                <td>$ {{round($ingr->sum * $ingr->precio_bruto)}}</td>
                <?php
                  $costo_total_tmp += round($ingr->sum * $ingr->precio_bruto);
                 ?>
                @endif
                @else
                <td>$ {{round($ingr->sum * $ingr->precio_bruto)}}</td>
                <?php
                  $costo_total_tmp += round($ingr->sum * $ingr->precio_bruto);
                 ?>
                @endif
                @if($ingr->inventareable == 1)
                <th><input class="form-control" id="{{$ingr->id_ingr}}" value="{{$ingr->stock}} {{$ingr->uni_inv}}" disabled></th>
                @else
                <th><input class="form-control" placeholder="No aplica inventario" disabled></th>
                @endif
              </tr>
              @endforeach
              <tfoot>
                <th></th>
                <th></th>
                <td><h4><strong>Total:</strong></td>
                <th><input class="form-control" name="total_costo_ingredientes[]" id="total_costo_ingredientes" value="{{$costo_total_tmp}}" required readonly="readonly"></th>
                <th></th>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8">
          <hr></hr>
          <h4>Ingredientes extras</h4>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
              <thead style= "background-color:#7FB3D5">
                <th class="col-lg-2 col-md-2 col-sm-2">Ingredientes</th>
                <th class="col-lg-2 col-md-2 col-sm-2">Porciones</th>
                <th colspan="2">Cantidad</th>

                <th class="col-lg-2 col-md-2 col-sm-2">Costo neto empresa</th>
                <th class="col-lg-2 col-md-2 col-sm-2">Precio venta neto cliente</th>

              </thead>
                @if($num_ingr_ext != 0)
                <tfoot>
                  <td><h4><strong>Total:</strong></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td><input class="form-control" type="number" name="total_costo_ingr_extra[]" id="total_costo_ingr_ext" required readonly="readonly"></td>
                  <th><input class="form-control" type="number" name="total_ingr_extra[]" id="total_ingr_ext" required readonly="readonly"></th>
                </tfoot>
                <?php $i = 0 ?>
              @foreach($ingr_extras as $ing_ext)
              <tr>
                <td>{{$ing_ext->nombre}}</td>
                <td>
                  {{$ing_ext->cantidad}}
                  <input class="hidden" name="cantidad_total_ingr_extra[]" value="{{$ing_ext->cantidad * $ing_ext->porcion_}}" readonly="readonly"/>
                </td>
                @if($ing_ext->uni_porcion == "gramos")
                <td><input class="form-control" readonly="readonly" value="{{$ing_ext->cantidad * $ing_ext->porcion_/1000}}"/></td>
                <td>Kg</td>
                @else
                <td><input class="form-control"  value="{{$ing_ext->cantidad * $ing_ext->porcion_}}" readonly="readonly"/></td>
                <td> {{$ing_ext->uni_porcion}}</td>
                @endif

                <td>
                  @if($ing_ext->uni_porcion == "gramos")
                  <input class="form-control" type="number" name="costo_ingr_extra[]" id="costo_ingr_ext_{{$i}}" onkeyup="calculo_precio_3({{$i}})" value="{{($ing_ext->cantidad*$ing_ext->porcion_*$ing_ext->precio_bruto)/1000}}" required>
                  @else
                  <input class="form-control" type="number" name="costo_ingr_extra[]" id="costo_ingr_ext_{{$i}}" onkeyup="calculo_precio_3({{$i}})" value="{{$ing_ext->cantidad*$ing_ext->porcion_*$ing_ext->precio_bruto}}" required>
                  @endif
                </td>
                <td>
                  <input class="form-control"type="number"  name="precio_ingr_extra[]" id="precio_ingr_ext_{{$i}}" onkeyup="calculo_precio_3({{$i}})" required>
                  <input name="id_etie[]" id="id_etie_{{$i}}" value="{{$ing_ext->id}}"  hidden>
                </td>

              </tr>
              <?php $i++ ?>
              @endforeach
              @else
              <tfoot>
                <td><h4><strong>Total:</strong></h4></td>
                <td></td>
                <td></td>
                <td></td>
                <th><input class="form-control" value="0" name="total_costo_ingr_extra[]" id="total_costo_ingr_ext" required readonly="readonly"></th>
                <th><input class="form-control" value="0" name="total_ingr_extra[]" id="total_ingr_ext" required readonly="readonly"></th>
              </tfoot>
              @endif
            </table>
          </div>
        </div>


      <div class="col-lg-4 col-md-4 col-sm-4k">
        <hr></hr>
        <h4>Extras del evento</h4>

        <div class="table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color:#F9E79F">
              <th class="col-lg-2 col-md-2 col-sm-2">Extras</th>
              <th class="col-lg-2 col-md-2 col-sm-2">Costo empresa neto</th>
              <th class="col-lg-2 col-md-2 col-sm-2">Precio venta neto cliente</th>
            </thead>
            @if($num_ext != 0)
            <tfoot>
              <td><h4><strong>Total:</strong></td>
              <th><input class="form-control" name="total_costo_extra[]" id="total_costo_ext" required readonly="readonly"></th>
              <th><input class="form-control" name="total_extra[]" id="total_ext" required readonly="readonly"></th>
            </tfoot>
            <?php $i = 0 ?>
            @foreach($extras as $ext)
            <tr>
              <td>{{$ext->valor}}</td>
              <th>
                  <input class="form-control" name="costo_extra[]" type="number" id="costo_ext_{{$i}}" onkeyup="calculo_precio_2({{$i}})" required>
              </th>
              <th>
                  <input class="form-control" type="number" name="precio_extra[]" id="precio_ext_{{$i}}" onkeyup="calculo_precio_2({{$i}})" required>
                  <input name="id_ete[]" id="id_ete_{{$i}}" value="{{$ext->id}}"  hidden>
              </th>

            </tr>
              <?php $i++ ?>
            @endforeach
            @else
            <tfoot>
              <td><h4><strong>Total:</strong></td>
              <th><input class="form-control" value="0" name="total_extra[]" id="total_ext" required readonly="readonly"></th>
              <th><input class="form-control" value="0" name="total_costo_extra[]" id="total_costo_ext" required readonly="readonly"></th>
            </tfoot>
            @endif
          </table>
        </div>
      </div>



      <div class="col-lg-8 col-md-6">
        <hr></hr>
        <div class="table-responsive">
          <div class="form-group">
              <h4>Items nuevos</h4>
          </div>
          <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color:#D2B4DE">
              <th>Nombre</th>
              <th>Cantidad</th>
              <th>Costo neto unidad</th>
              <th>Costo bruto unidad</th>
              <th>Precio neto unidad</th>
              <th>Precio bruto unidad</th>
            </thead>

              @foreach($nuevos_items as $ni)
              <tr>
                <td>{{$ni->nombre}}</td>
                <td><input value="{{$ni->cantidad}}" class="form-control" required/></td>
                <td><input value="{{$ni->costo_neto_unidad}}" class="form-control" required/></td>
                <td><input value="" class="form-control" required/></td>
                <td><input value="{{$ni->precio_neto_unidad}}" class="form-control" required/></td>
                <td><input value="" class="form-control" required/></td>
              </tr>
              @endforeach

          </table>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
      <hr></hr>
      <div class="table-responsive">
        <div class="form-group">
            <h4>Trabajadores</h4>
        </div>
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#D2B4DE">
            <th>Pago líquido por cocinero</th>
            <th>Cantidad cocineros</th>
          </thead>
          <tr>
            <th>
              <input class="form-control" onkeyup="calculo_costos_total()" name="monto_cocineros" id="monto_cocineros" type="number" placeholder="$20000" required/>
              <input id="total_cocineros" type="hidden"/>
            </th>
            <th>
              <input class="form-control" name="cantidad_cocineros" value="0"/>
            </th>
          </tr>
        </table>
      </div>
    </div>

      <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <h4>Totales</h4>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color:#D2B4DE">
              <th>Costo parcial neto</th>
              <th>Costo parcial bruto</th>
              <th>Ganancia neta</th>
              <th>Ganancia bruta</th>
              <th>(%) estimado de ganancia</th>
              <th>Total Neto</th>
              <th>Iva</th>
              <th>Monto a cobrar evento (Bruto)</th>
            </thead>
            <tr>
              <th><input name="costo_parcial"  class="form-control" placeholder="" id="costo_parcial" readonly="readonly"></th>
              <th></th>
              <th></th>
              <th><input name="total_iva"  class="form-control" placeholder="" id="iva_total" readonly="readonly"></th>
              <th>
                <input name="total_final"   class="form-control" placeholder="" id="Display" readonly="readonly">
                <input name="pago_cocinero" type="hidden" id="pago_cocinero" value="0" >
                <input name="total_iva" type="hidden"id="IVA">
              </th>
            </tr>
          </table>
        </div>
      </div>
        </div>
      <div class="row" id="save" hidden>
        <hr></hr>
        <input name="_token value={{csrf_token()}}" type="hidden"></input>
        <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">


        <button class="btn btn-primary" type="submit">Terminar cotización</button>

        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>
      </div>

{!!Form::close()!!}
@endsection
