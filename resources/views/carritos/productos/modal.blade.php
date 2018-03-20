<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
id="modal-delete-{{$prod->id}}">
{{Form::Open(array('action'=>array('ProductosController@destroy', $prod->id), 'method'=>'delete'))}}
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">x</span>
      </button>
      <h4 class="modal-title">Eliminar producto</h4>
    </div>
    <div class="modal-body">
      <p>¿Estás seguro que deseas eliminar el producto <strong>{{$prod->nombre}}</strong>
        de las lista de productos?</p>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Si</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
    </div>
  </div>
</div>
{{Form::close()}}
