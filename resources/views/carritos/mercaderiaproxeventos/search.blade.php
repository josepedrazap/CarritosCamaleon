{!! Form::open(array('url'=>'carritos/mercaderiaproxeventos', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search'))!!}
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <div class="form-group">
            <select name="dias" onchange="this.form.submit()" class="form-control selectpicker">
              <option>mostrar próximos...</option>
              @for($i = 1; $i < 30; $i++)
              <option value="{{$i}}">{{$i}} días</option>
              @endfor
            </select>
          </div>
        </div>
      </div>
{{Form::close()}}
