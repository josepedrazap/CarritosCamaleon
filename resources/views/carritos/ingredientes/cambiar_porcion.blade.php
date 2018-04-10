<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
id="modal-cambiar-{{$ingr->id}}">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">x</span>
      </button>
      <h4 class="modal-title">Cambiar porción</h4>
    </div>
    <div class="modal-body">
      <p>Nueva porción para el ingrediente {{$ingr->nombre}}</p>
      <div class="col-lg-6">
        <div class="form-group">
          <input class="form-control" name="porcion" />
        </div>
        <div class="form-group">
          <input class="form-control" name="porcion" />
        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Actualizar</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
    </div>
  </div>
</div>
