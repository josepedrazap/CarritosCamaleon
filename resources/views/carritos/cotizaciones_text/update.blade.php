<div>

      <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.css">

{!!Form::open(array('url'=>'carritos/cotizaciones_actualizar','method'=>'GET','autocomplete'=>'off'))!!}
{{Form::Token()}}

<div>
  <input class="form-control" name="fecha" value="{{$cotizacion->fecha}}"/>
  <input class="form-control hidden"  name="id" value="{{$cotizacion->id}}"/>
</div>

  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="nombre_cliente">Nombre cliente</label>
      <input type="text" value="{{$cotizacion->nombre_cliente}}" name="nombre_cliente" class="form-control" required placeholder="nombre...">
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="direccion">Dirección</label>
      <input type="text" value="{{$cotizacion->direccion}}" name="direccion" class="form-control" required placeholder="dirección...">
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="form-group">
      <label for="comentarios">Descripción</label>
      <textarea name="descripcion" class="form-control" rows="4" required placeholder="Descripción del evento">{{$cotizacion->descripcion}}</textarea>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
    <div class="form-group">
      <label for="estado">Estado de cotización</label>
      <select type="text" name="estado" id="estado" class="form-control" placeholder="dirección...">
        @if($cotizacion->estado == 0)
        <option selected value="0">No respondido</option>
        <option value="1">Respondido</option>
        <option value="2">Respondido con insistencia</option>
        <option value="3">Insistir</option>
        @elseif($cotizacion->estado == 1)
        <option value="0">No respondido</option>
        <option selected value="1">Respondido</option>
        <option value="2">Respondido con insistencia</option>
        <option value="3">Insistir</option>
        @elseif($cotizacion->estado == 2)
        <option value="0">No respondido</option>
        <option value="1">Respondido</option>
        <option selected value="2">Respondido con insistencia</option>
        <option value="3">Insistir</option>
        @elseif($cotizacion->estado == 3)
        <option value="0">No respondido</option>
        <option value="1">Respondido</option>
        <option value="2">Respondido con insistencia</option>
        <option selected value="3">Insistir</option>
        @endif
      </select>
    </div>
  </div>

  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
    <label for="estado">.</label>

    <div class="form-group">
      <button type="submit" class="btn btn-success">Actualizar</button>
      <button type="button" class="btn btn-danger">Descartar</button>
    </div>
  </div>

</div>

{!!Form::close()!!}
