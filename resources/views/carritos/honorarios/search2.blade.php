{!! Form::open(array('url'=>'carritos/honorarios', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search'))!!}
<hr></hr>
<div class="row">
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

    <div class="form-group">
      <input name="date_1" type="date" value="{{$date_1}}" class="form-control"/>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <div class="form-group">
      <input name="date_2" value="{{$date_2}}" type="date" class="form-control"/>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <div class="form-group">
      <button type="submit" class="btn btn-success">Ir</button>
    </div>
  </div>
</div>


{{Form::close()}}
