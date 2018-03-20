<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
id="modal-delete-{{$eve->id}}">
{{Form::Open(array('action'=>array('CotizacionesController@destroy', $eve->id), 'method'=>'delete'))}}
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">x</span>
      </button>
      <h4 class="modal-title">Cancelar cotizacion</h4>
    </div>
    <div class="modal-body">
      <p>¿Está seguro que desea cancelar la cotizacion id número {{$eve->id}} del cliente <strong>{{$eve->nombre_cliente}}</strong>
        programada para la fecha <strong>{{$eve->fecha_hora}}</strong>?</p>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Si</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
    </div>
  </div>
</div>
{{Form::close()}}
