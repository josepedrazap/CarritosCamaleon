@extends ('layouts.admin')
@section('contenido')

<script>
total = 0;
sub_total = 0;
cont = 0;

function submit_(){
  if(confirm("Asegurate de realizar un deposito por $" + total)){
    document.getElementById("myFormulario").submit();

  }
}

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

{!!Form::open(array('url'=>'carritos/pagos','method'=>'post', 'id'=>'myFormulario','autocomplete'=>'off'))!!}
{{Form::Token()}}

  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Pagos pendientes a {{$data[0]->nombre}} {{$data[0]->apellido}}</h3>
      <hr></hr>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h4>Datos del trabajador</h4>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
      <div class="form-group">
        <label >Banco</label>
        <input type="text" class="form-control" value="{{$data[0]->banco}}" disabled>
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
      <div class="form-group">
        <label >Tipo de cuenta</label>
        <input type="text" class="form-control" value="{{$data[0]->tipo_cuenta}}" disabled>
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
      <div class="form-group">
        <label >Cuenta</label>
        <input type="text" class="form-control" value="{{$data[0]->cuenta}}" disabled>
        <hr></hr>
        <label >Eventos a pagar</label>
        <input id="cont" type="text" name="cont" class="form-control" placeholder="Eventos pagados..." disabled>
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
      <div class="form-group">
        <label >Rut</label>
        <input type="text" class="form-control" value="{{$data[0]->rut}}" disabled>
        <hr></hr>
        <label>Total a transferir</label>
        <input id="total" type="text" name="total" class="form-control" placeholder="Total" disabled>
      </div>
    </div>

  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <h4>Eventos trabajados</h4>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Nombre cliente</th>
            <th>Fecha</th>
            <th>Estado evento</th>
            <th>Monto adeudado líquido</th>
            <th>Retención 10%</th>
            <th>Total</th>
            <th>Opciones</th>
          </thead>
          @foreach($data as $dat)
          <tr>
            <td>{{$dat->nombre_cliente}}</td>
            <td>{{$dat->fecha_hora}}</td>
            @if($dat->condicion == 1)
            <td style="color:orange"> <strong>No realizado</strong></td>
            @endif
            @if($dat->condicion == 2)
            <td style="color:green"> <strong>Realizado</strong></td>
            @endif
            @if($dat->condicion == 3)
            <td style="color:grey"> <strong>Cancelado</strong></td>
            @endif
            <td>
              <input value="{{$dat->monto}}" class="form-control" disabled id="{{$dat->id}}">
              <input name="{{$dat->id}}" value="0" hidden id="__{{$dat->id}}">
              <input name="id_pago[]" value="{{$dat->id}}" hidden>
            </td>
            <td>
              <input value="{{round(($dat->monto / 0.9) - $dat->monto)}}" class="form-control" disabled id="{{$dat->id}}">
            </td>
            <td>
              <input value="{{round($dat->monto / 0.9)}}" class="form-control" disabled id="{{$dat->id}}">
            </td>
            <td>
              <button type="button" id="_{{$dat->id}}" onclick="sumar({{$dat->id}})" class="btn btn-success">Pagar</button>
            </td>
          </tr>
          @endforeach
        </table>
      </div>

    </div>
  </div>
  <div class="form-group" id="save">
    <input name="id_t" value="{{$id_t}}" hidden>
    <input name="_token value={{csrf_token()}}" type="hidden"></input>
    <input type="button" class="btn btn-primary" value="Realizar pagos" onclick="submit_()"/>
    <button class="btn btn-danger" type="reset">Reiniciar</button>
  </div>
  {!!Form::close()!!}
@endsection
