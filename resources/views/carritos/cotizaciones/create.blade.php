@extends ('layouts.admin')
@section('contenido')

<script>
a = 0;
e = 0;

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
      v2 = $("#val1").val();
      v3 = $("#pago_cocinero").val();
      total = parseFloat(v1) + parseFloat(v2) + parseFloat(v3);

      document.getElementById('val2').value = parseFloat(v1);
      document.getElementById('IVA').value = parseFloat(total * 0.19);
      document.getElementById('Display').value = total;

    //  document.getElementById('total_sum').value = aux1;
      //document.getElementById('iva_sum').value = aux2;
}
function suma_cocinero(i){

  if(i == 1){
    total = parseFloat($("#pago_cocinero").val()) + {{$pago_cocinero}};
    document.getElementById('pago_cocinero').value = total;
    calculo_total();
  }else{
    total = parseFloat($("#pago_cocinero").val()) - {{$pago_cocinero}};
    document.getElementById('pago_cocinero').value = total;
    calculo_total();
  }
}
function eliminar_coc(){
  //total= total-subtotal[index];
  if(a > 0){
    sv = "#tra_" + a;
    $(sv).remove();
    suma_cocinero(0);
    a--;
  }
}
function addCocinero(){
      a++;

        var div = document.createElement('div');
        div.setAttribute('class', 'form-inline');
            div.innerHTML ='<div id="tra_'+a+'" style="clear:both" class="trabajador_'+a+' col-lg-6 col-md-6 col-sm-6 col-xs-6"><select id="select'+a+'" class="form-control" name="trabajador[]"></select></div>';
            document.getElementById('trabajadores').appendChild(div);

            sv = "#select"+a;
            llenar_select_trabajadores(a,sv);
            suma_cocinero(1);

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
function calculo_precio(t, i){
  var total = 0;
  v1 = $("#precio_real_" + i).val();
  v2 = $("#cant_prod_" + i).val();
  total =  0.19 * parseInt(v1) * parseInt(v2);
  document.getElementById('IVA_por_pagar_'+i).value = total;
  total = parseInt(v1) * parseInt(v2);
  document.getElementById('total_'+i).value = total;
  calculo_total_productos();
}
function calculo_total_productos(){
  var total = 0;
  var aux1 = 0;
  var aux2 = 0;
  for(i=0; i<2; i++){
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
    <h3>Despachar evento </h3>
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

    {!!Form::open(array('url'=>'carritos/cotizaciones','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::Token()}}

    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <h4>Datos del evento<input hidden name="id_evento_" value="{{$evento[0]->id}}"></h4>
        <hr></hr>
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
          <label for="fecha_cliente">Fecha y hora del evento</label>
          <h4>{{$evento[0]->fecha_hora}}</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
          <label for="fecha_cliente">Fecha y hora del despacho</label>
          <h4>{{$evento[0]->fecha_despacho}}</h4>
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
              <th>Precio unidad</th>
              <th>Precio total</th>
              <th>Precio costo</th>
              <th>Precio neto</th>
              <th>IVA por pagar</th>
              <th>Total</th>
            </thead>
            <tfoot>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th><h4><strong>Total:</strong></h4></th>
              <th><input class="form-control" name="iva_sum" id="iva_sum" placeholder="Iva total" disabled></th>
              <th><input class="form-control" name="total_sum" id="total_sum" placeholder="Total"disabled></th>
            </tfoot>

            <?php $i = 0 ?>

            @foreach($productos as $prod)
            <tr>
              <td>{{$prod->nombre}}</td>
              <td><input class="form-control" id="cant_prod_{{$i}}" value="{{$prod->cantidad}}" disabled></td>
              <td>${{$prod->precio}}</td>
              <td>${{$prod->precio * $prod->cantidad}}</td>

              <td>${{$prod->precio * $prod->cantidad}}</td>
              <th><input name="precio_real[]" type="number" id="precio_real_{{$i}}" onkeyup="calculo_precio(this.value, {{$i}})" class="form-control"></th>
              <th><input name="IVA_por_pagar[]" id="IVA_por_pagar_{{$i}}" disabled class="form-control"></th>
              <th><input name="total_[]" id="total_{{$i}}" disabled class="form-control"></th>

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
              <th class="col-lg-2 col-md-2 col-sm-2">Cantidad</th>
            </thead>
            @foreach($extras as $ext)
            <tr>
              <td>{{$ext->nombre}}</td>
              <td>{{$ext->cantidad}}</td>
            </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#F9E79F">
          <th class="col-lg-2 col-md-2 col-sm-2">Extras</th>
          <th class="col-lg-2 col-md-2 col-sm-2">Cantidad</th>
        </thead>
        @foreach($ingredientes_producto as $ext)
        <tr>
          <td>{{$ext->nombre}}</td>
          <td>{{$ext->cantidad}}</td>
        </tr>
        @endforeach
      </table>
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

              <th>Stock en inventario</th>

            </thead>
            @foreach($ingredientes as $ingr)
            <tr>
              <td>{{$ingr->nombre}}</td>
              @if($ingr->unidad == "gramos" || $ingr->unidad == "unidad")
              @if($ingr->unidad == "gramos")
              <td>{{$ingr->sum / 1000}} kg</td>
              @endif
              @if($ingr->unidad == "unidad")
              <td>{{ceil($ingr->sum)}} unidades</td>
              @endif
              @else
              <td>{{$ingr->sum}} unidades</td>
              @endif
              @if($ingr->inventareable == 1)
              <th><input class="form-control" id="{{$ingr->id_ingr}}" value="{{$ingr->stock}} {{$ingr->uni_inv}}" disabled></th>
              @else
              <th><input class="form-control" placeholder="No aplica inventario" disabled></th>
              @endif
            </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>

    <div class="row">
      <hr></hr>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
        <h4>Asignar cocineros</h4>
        <input type="button" class="btn btn-success" onClick="addCocinero()" value="+ Agregar cocinero" />
        <input type="button" class="btn btn-danger" onClick="eliminar_coc()" value="X" />
        </div>
        <div class="form-group">
          <div class="row" id="contenedor1">
          </div>
          <div class="row" id="trabajadores">
          </div>
        </div>
      </div>

      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <h4>Costo total y extras</h4>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color:#D2B4DE">
              <th>Costo parcial evento</th>
              <th>Gasto en movilización</th>
              <th>Iva por pagar total</th>
              <th>Monto a cobrar evento</th>
            </thead>
            <tr>
              <td><input name="total_pre" class="form-control" id="val2"  disabled></td>
              <td><input name="extra_movil" class="form-control" value="0" id="val1" onkeyup="calculo_total()" required></td>
              <td><input name="total_iva" class="form-control" placeholder="" id="IVA" disabled></td>
              <td><input name="total_final" class="form-control" placeholder="" id="Display" disabled></td>
              <td><input name="pago_cocinero" class="form-control" placeholder="" id="pago_cocinero" value="0" type="hidden"></td>

            </tr>
          </table>
        </div>
      </div>

      </div>
    </div>
      <div class="form-group ">
        <hr></hr>
        <input name="_token value={{csrf_token()}}" type="hidden"></input>
        <button class="btn btn-primary" type="submit">Despachar</button>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>
{!!Form::close()!!}
@endsection
