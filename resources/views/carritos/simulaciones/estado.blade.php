@extends ('layouts.admin')
@section('contenido')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
  function realizar_cancelar(i){
    var id = $("#id_evento").val();
    if(i == 1){
      if(confirm("¿Estás seguro que deseas marcar el evento como realizado? Esta acción liberará los pagos a los cocineros y dejará el evento inmodificable.")){
        axios.get('/realizar_cancelar?id=' + id + '&i=1')
        .then(function (response) {
          location.reload();
          console.log(response);
        })
        .catch(function (error) {
          // handle error
          console.log(error);
        })
        .then(function () {
          // always executed
        });
      }
    }else{
      if(confirm("¿Estás seguro que deseas cancelar el evento? Esto inhabilitará el evento. Esta acción no se puede deshacer")){
        axios.get('/realizar_cancelar?id=' + id + '&i=0')
        .then(function (response) {
          location.reload();
          console.log(response);
        })
        .catch(function (error) {
          // handle error
          console.log(error);
        })
        .then(function () {
          // always executed
        });
      }
    }
  }

</script>
<div class="row">
  <div class="col-md-6">
    <h4>Estado del evento</h4>
    <input value="{{$evento[0]->id}}" hidden id="id_evento"/>
  </div>
</div>
<hr></hr>

<div class="row">
  <div class="col-md-6">
    <label>Estado del pago</label>
    @if($evento[0]->estado_pago == 2)
    <h5 style="color:green">Pagado</h5>
    @elseif($evento[0]->estado_pago == 1)
    <h5 style="color:orange">Pagado parcialmente</h5>
    @elseif($evento[0]->estado_pago == 0)
    <h5 style="color:red">No pagado</h5>
    @endif
  </div>
  <div class="col-md-6">
    <label>Estado de ejecución</label>
    @if($evento[0]->condicion == 1)
    <h5 style="color:orange">Por realizar</h5>
    @elseif($evento[0]->condicion == 2)
    <h5 style="color:green">Realizado</h5>
    @elseif($evento[0]->condicion == 3)
    <h5 style="color:red">Cancelado</h5>
    @endif
  </div>
</div>
<hr></hr>
<div class="row">
  <div class="col-md-6">
    <h4>Cambiar estado</h4>
  </div>
</div>
<hr></hr>

<div class="row">
  <div class="col-md-6">
    <label for="">Con respecto al pago</label>
  </div>
  <div class="col-md-6">
    <label for="">Con respecto a la ejecución</label>
  </div>
</div>
<div class="row">
  <div >
  <div class="col-md-2">
    <h5>Pago registrado: </h5>
    <input class="form-control" type="number" name="pago" readonly="readonly"/>
  </div>
  <div class="col-md-2">
    <h5>Ingresar nuevo pago: </h5>
    <input class="form-control" type="number" name="pago_nuevo"/>
  </div>
  <div class="col-md-2">
    <h5>Guardar: </h5>
    <input class="btn btn-primary" value="Ingresar" type="button" name="pago_nuevo"/>
  </div>
</div>

@if($evento[0]->condicion == 1)
<div>
<div class="col-md-1">
  <h5>Realizar: </h5>
  <input class="btn btn-success" value="Realizar" type="button" onclick="realizar_cancelar(1)" name="pago_nuevo"/>
</div>
<div class="col-md-1">
  <h5>Cancelar:</h5>
  <input class="btn btn-danger" value="Cancelar" type="button" onclick="realizar_cancelar(0)" name="pago_nuevo"/>
</div>
</div>
@endif

</div>
@endsection
