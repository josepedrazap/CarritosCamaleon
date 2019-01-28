@extends('layouts.admin')
@section('contenido')
<html>
<head>
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script>
  function datetimepick(){
    $('#fecha_hora').datetimepicker();
  }
  function agregar_cliente(){
    window.open("carritos/clientes/create", "Agregar cliente")

  }
  function terminar(){
      if($("#cliente").val() == "0" || $("#direccion").val() == "" || $("#fecha_hora").val() == ""){
        alert("Rellena los campos cliente, fecha y dirección.")
        return
      }
      var x = $("#len_trabajadores").val();
      for(var i = 0; i < x; i++){
        if(document.getElementsByName("trabajadores[]")[i].value == "0"){
          alert("Selecciona todos los trabajadores")
          return
        }
      }
      if(confirm("¿Estás seguro de que has completado correctamente los campos? Ésta acción no puede deshacerse.")){
        document.getElementById("myFormulario").submit();
      }
  }
</script>
</head>

<body>
  <form action="/conv_sim_a_evento" id="myFormulario" method="get">
  <input name="id" hidden value="{{$id}}" />
<div class="row">
  <div class="col-md-6">
    <h4>Resumen Simulación</h4>
  </div>
  <div class="col-md-6">
    <h4>Descripción de  Simulación</h4>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <table table class="table table-striped table-bordered table-condensed table-hover">
      @foreach($productos as $prods)
      <tr>
        <td>{{$prods->cantidad}} {{$prods->nombre}} a ${{$prods->precio_neto_unidad}} netos</td>
      </tr>
      @endforeach
      @foreach($ingredientes_extras as $ingr_ext)
      @if($ingr_ext->unidad == "Kg")
      <tr>
        <td>{{$ingr_ext->cantidad * $ingr_ext->porcion_unitaria / 1000}} {{$ingr_ext->unidad}} de {{$ingr_ext->nombre}} con un costo de ${{$ingr_ext->costo_neto_unidad}} netos vendidos en ${{$ingr_ext->precio_neto_unidad}} netos</td>
      </tr>
      @else
      <tr>
        <td>{{$ingr_ext->cantidad * $ingr_ext->porcion_unitaria}} {{$ingr_ext->unidad}}(s) de {{$ingr_ext->nombre}} con un costo de ${{$ingr_ext->costo_neto_unidad}} netos vendidos en ${{$ingr_ext->precio_neto_unidad}} netos</td>
      </tr>
      @endif
      @endforeach
      @foreach($extras as $ext)
      <tr>
        <td>{{$ext->cantidad}} {{$ext->valor}}(s) con un costo de ${{$ext->cantidad * $ext->costo_neto_unidad}} netos vendidos en ${{$ext->precio_neto_unidad}} netos</td>
      </tr>
      @endforeach
      @foreach($nuevos as $nue)
      <tr>
        <td>{{$nue->cantidad}} {{$nue->nombre}} con un costo de ${{$nue->cantidad * $nue->costo_neto_unidad}} netos vendido en ${{$nue->cantidad * $nue->precio_neto_unidad}} netos</td>
      </tr>
      @endforeach

      @foreach($trabajadores as $tra)
      <tr>
        <td>{{$tra->cantidad}} {{$tra->nombre}}(s) a un total de ${{$tra->sueldo}} líquidos</td>
      </tr>
      @endforeach
    </table>
  </div>
  <div class="col-md-6">
    <textarea class="form-control" name="descripcion" rows="5">{{$sim[0]->descripcion}}</textarea>
  </div>
</div>
<div class="row">
  <div class="col-md-4">
    <h4>Rellena los siguientes campos</h4>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
    <label>Selecciona el cliente</label>
    <select class="form-control" name="cliente" id="cliente">
      <option value="0">---</option>
      @foreach($clientes as $cli)
        <option value="{{$cli->id}}">{{$cli->nombre}} {{$cli->apellido}}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-1">
    <label>Agregar</label>
    <input class="btn btn-info" onclick="agregar_cliente()" type="button" value="+"/>
  </div>
  <div class="col-md-4">
    <label>Fecha y hora del evento</label>
    <input type="datetime-local" class="form-control" id="fecha_hora" onclick="datetimepick()" name="fecha_hora" id="fecha_hora" required/>
  </div>
  <div class="col-md-3">
    <label>Dirección</label>
    <input class="form-control" name="direccion" id="direccion" type="text" required />
  </div>
  <div class="col-md-1">
    <label>Buscar</label>
    <input class="btn btn-info" type="button" value="Lupa"/>
  </div>
</div>
<hr></hr>
<div class="row">
  <div class="col-md-4">
    <h4>Selecciona a los trabajadores</h4>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
  <table table class="table table-striped table-bordered table-condensed table-hover">
    <thead style="background-color:#ABEBC6">
      <th>Tipo</th>
      <th>Nombre</th>
      <th>Pago líquido</th>
      <th>Retención</th>
      <th>Total</th>
    </thead>
      <?php $len_trabajadores = 0; ?>
      @foreach($trabajadores as $tra)
        @for($i = 0; $i < $tra->cantidad; $i++)
          <tr>
            <td><input name="trabajo[]" value="{{$tra->nombre}}" class="form-control" readonly="readonly"/></td>
            <td>
              <?php $len_trabajadores++; ?>
              <select class="form-control" name="trabajadores[]">
                <option value="0">---</option>
                @foreach($trabajadores_lista as $trl)
                  @if($trl->maneja)
                  <option value="{{$trl->id}}">{{$trl->nombre}} {{$trl->apellido}} (Sí maneja)</option>
                  @else
                  <option value="{{$trl->id}}">{{$trl->nombre}} {{$trl->apellido}} (No maneja)</option>
                  @endif
                @endforeach
              </select>
            </td>
          <td><input value="{{$tra->sueldo / $tra->cantidad}}" class="form-control" name="monto[]" readonly="readonly"/></td>
          <td>${{(round($tra->sueldo / 0.9) - $tra->sueldo) / $tra->cantidad}}</td>
          <td>${{(round($tra->sueldo / 0.9))/$tra->cantidad}}</td>
        </tr>
        @endfor
      @endforeach
      <input hidden value="{{$len_trabajadores}}" id="len_trabajadores"/>
  </table>
</div>
</div>
<hr></hr>
<div class="row">
  <div class="col-md-4">
    <h4>Totales</h4>
  </div>
</div>
<div class="row">
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
      <th><input name="costo_parcial_neto"  class="form-control" value="{{$simulacion[0]->costo_parcial_neto}}" readonly="readonly"></th>
      <th><input name="costo_parcial_bruto"  class="form-control" value="{{$simulacion[0]->costo_parcial_bruto}}" readonly="readonly"></th>
      <th><input name="ganancia_neta"  class="form-control" value="{{$simulacion[0]->ganancia_neta}}" readonly="readonly"></th>
      <th><input name="ganancia_bruta"  class="form-control" value="{{$simulacion[0]->ganancia_bruta}}" readonly="readonly"></th>
      <th><input name="porcentaje_ganacia"   class="form-control" value="{{$simulacion[0]->porcentaje_ganacia}}" readonly="readonly"></th>
      <th><input name="total_final_neto"   class="form-control" value="{{$simulacion[0]->total_final_neto}}" readonly="readonly"></th>
      <th><input name="total_final_iva"   class="form-control" value="{{$simulacion[0]->total_final_iva}}" readonly="readonly"></th>
      <th><input name="total_final_bruto"   class="form-control" value="{{$simulacion[0]->total_final_bruto}}" readonly="readonly"></th>
    </tr>
  </table>
</div>
</form>
<hr></hr>
<div class="row">
  <div class="col-md-4">
    <input class="btn btn-success" onclick="terminar()" value="Terminar" />
  </div>
</div>

</body>
</html>
@endsection
