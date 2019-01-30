<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
id="modal-delete-{{$eve->id}}">
<form action="/eliminar_evento" id="myFormulario" method="get">
<input class="hidden" name="id" value="{{$eve->id}}" />
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">x</span>
      </button>
      <h4 class="modal-title">Eliminar</h4>
    </div>
    <div class="modal-body">
      <p>¿Estás seguro de que deseas eliminar el evento {{$eve->id}}?</p>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Si</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
    </div>
  </div>
</div>
</form>
