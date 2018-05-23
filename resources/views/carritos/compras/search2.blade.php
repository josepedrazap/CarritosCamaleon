{!! Form::open(array('url'=>'carritos/compras', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search'))!!}
<hr></hr>
<div class="row">
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <div class="form-group">
      <label>Desde: </label>
      <input name="date_1" type="date" value="{{$date_1}}" class="form-control"/>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <div class="form-group">
      <label>Hasta: </label>
      <input name="date_2" value="{{$date_2}}" type="date" class="form-control"/>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <div class="form-group">
      <label>Comprobante: </label>
      <input name="num_com" value="{{$num_com}}" type="number" class="form-control"/>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <div class="form-group">
      <button type="submit" class="btn btn-success">Buscar</button>
    </div>
  </div>
</div>


{{Form::close()}}
