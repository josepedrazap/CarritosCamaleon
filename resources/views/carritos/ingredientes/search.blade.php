{!! Form::open(array('url'=>'carritos/ingredientes', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search'))!!}

<div class="form-group">
  <div class="input-group">
    <input type="text" class="form-control mr-sm-2" name="searchText" placeholder="buscar...">
    <span class="input-group-btn">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </span>
  </div>
</div>


{{Form::close()}}
