<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
id="modal-sub">
{{Form::Open(array('action'=>array('DespachoController@store', $id = $evento[0]->id), 'method'=>'POST'))}}
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">x</span>
      </button>
      <h4 class="modal-title">Despachar evento</h4>
    </div>
    <div class="modal-body">
      <p>¿Está seguro que desea despachar el evento id número {{$evento[0]->id}} del cliente <strong>{{$evento[0]->nombre_cliente}}</strong>
        programado para la fecha <strong>{{$evento[0]->fecha_hora}}</strong>?</p>
        <p>Si despacha el evento se descontarán los ingredientes del inventario y se asumirá el pago*</p>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Si</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
    </div>
  </div>
</div>
{{Form::close()}}
