{!! Form::open(array('url'=>'carritos/cotizaciones', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search'))!!}
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
              <option>Ordenar por...</option>
              <option value="3">todos</option>
              <option value="1">esta semana</option>
              <option value="2">hoy</option>
            </select>
          </div>
        </div>
      </div>


{{Form::close()}}
