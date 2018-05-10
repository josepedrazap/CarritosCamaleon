{!! Form::open(array('url'=>'carritos/ingredientes', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search'))!!}
<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
    <div class="form-group">
      <div class="form-group">
        <div class="input-group">
          <input type="text" class="form-control" name="searchText" placeholder="buscar...">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-primary">Buscar</button>
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <div class="form-group">
      <select name="tipo" onchange="this.form.submit()" class="form-control selectpicker">
        <option value="0">Ordenar por...</option>
        <option value="verdura">verdura</option>
        <option value="lacteo">lacteo</option>
        <option value="proteina">proteina</option>
        <option value="masa">masa</option>
        <option value="refrigerio">refrigerio</option>
        <option value="insumo">insumo</option>
      </select>
    </div>
  </div>
</div>


{{Form::close()}}
