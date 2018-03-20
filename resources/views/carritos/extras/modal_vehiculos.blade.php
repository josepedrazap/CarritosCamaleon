<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
id="modal-delete-{{$veh->id}}">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">x</span>
      </button>
      <h4 class="modal-title">Eliminar vehículo</h4>
    </div>
    <div class="modal-body">
      <p>¿Está seguro que desea eliminar el vehículo alias {{$veh->alias}} ?</p>
    </div>
    <div class="modal-footer">
      <a href="/carritos/extras/eliminar_vehiculo/{{$veh->id}}" ><button type="submit" class="btn btn-primary">Si</button></a>
      <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
    </div>
  </div>
</div>
