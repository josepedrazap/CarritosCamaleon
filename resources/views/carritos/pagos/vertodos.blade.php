@extends ('layouts.admin')
@section('contenido')

<script>
total = 0;
sub_total = 0;
cont = 0;

function sumar(id){
  sub_total = $("#" + id).val();
  cont++;
  total =  parseInt(total) +  parseInt(sub_total);
  document.getElementById("total").value=total;
  document.getElementById("cont").value=cont;
  document.getElementById("__"+id).value= 1;
  $("#_" + id).prop("class", "btn btn-succes");
  $("#_" + id).prop("onclick", "");
}
function finalizar(){
  alert("finalizar");
  this.form.submit();
}
</script>
@if($cont != 0)
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Todos los pagos a {{$data[0]->nombre}} {{$data[0]->apellido}}</h3>
      <hr></hr>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
      <div class="form-group">
        <label >Total eventos trabajados</label>
        <input type="text" class="form-control" value="{{$cont}}" disabled>
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
      <div class="form-group">
        <label >Total monto pagado</label>
        <input type="text" class="form-control" value="{{$sum}}" disabled>
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
      <div class="form-group">
        <label >Total monto adeudado</label>
        <input type="text" class="form-control" value="{{$sum_}}" disabled>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <h4>Eventos trabajados</h4>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Estado de pago</th>
            <th>Nombre cliente</th>
            <th>Fecha</th>
            <th>Estado evento</th>
            <th>Monto</th>
          </thead>
          @foreach($data as $dat)
          <tr>
            @if($dat->estado == 1)
            <td style="color:orange"> <strong>Pendiente</strong></td>
            @endif
            @if($dat->estado == 2)
            <td style="color:green"> <strong>Pagado</strong></td>
            @endif
            <td>{{$dat->nombre_cliente}}</td>
            <td>{{$dat->fecha_hora}}</td>
            @if($dat->condicion == 1)
            <td style="color:orange"> <strong>Pendiente</strong></td>
            @endif
            @if($dat->condicion == 2)
            <td style="color:green"> <strong>Despachado</strong></td>
            @endif
            @if($dat->condicion == 3)
            <td style="color:grey"> <strong>Ejecutado</strong></td>
            @endif
            <td>
              <input value="{{$dat->monto}}" class="form-control" disabled id="{{$dat->id}}">
              <input name="{{$dat->id}}" value="0" hidden id="__{{$dat->id}}">
              <input name="id_pago[]" value="{{$dat->id}}" hidden>
            </td>
          </tr>
          @endforeach
        </table>
      </div>
      {{$data->render()}}
    </div>
  </div>
  <div class="form-group" id="save">
    <button class="btn btn-danger" onclick="history.back(-1)">Volver</button>
  </div>
@else
<h4>El trabajador no posee pagos pendientes.</h4>
@endif
@endsection
