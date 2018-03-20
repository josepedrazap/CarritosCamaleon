@extends ('layouts.admin')
@section('contenido')
<script>
  function block(){
    if (document.getElementById('check').checked){
      $("#rut").removeAttr("readonly","readonly");
      $("#tipo_documento").removeAttr("readonly","readonly");
      $("#rut").attr("required");
      $("#tipo_documento").attr("required","required");
    }else{
      $("#rut").attr("readonly","readonly");
      $("#tipo_documento").attr("readonly","readonly");
    }
  }
</script>
<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Agregar cuenta</h3>
    <hr/>
    @if(count($errors)>0)
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
      </ul>
    </div>
    @endif
  </div>
</div>

    {!!Form::open(array('url'=>'carritos/cuentas_contables','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::Token()}}

    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <h4>Datos de la cuenta</h4>
      </div>
    </div>

    <div class="row col-lg-9 col-md-9">
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <div class="form-group">
          <label for="nombre">Nombre de la cuenta</label>
          <input type="text" name="nombre" class="form-control" placeholder="nombre..." required>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <div class="form-group">
          <label>Prefijo de la cuenta</label>
          <select class="form-control" name="prefijo" required>
            @foreach($prefijos as $pf)
            <option value="{{$pf->prefijo}}" >{{$pf->prefijo}} {{$pf->valor}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <div class="form-group">
          <label>Tipo de cuenta</label>
          <select class="form-control" name="tipo" required>
            @foreach($tipos as $tp)
            <option value="{{$tp->valor}}">{{$tp->valor}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="row col-lg-9 col-md-9 col-sm-9">
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <div class="form-group">
          <label for="email">Aplica auxiliares?</label>
          <div class="input-group">
            <span class="input-group-addon">
              <label>SÍ <input type="checkbox" name="check" id="check" onclick="block()" aria-label="..."></label>
            </span>
          </div><!-- /input-group -->
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <div class="form-group">
          <label for="nombre">Rut</label>
          <input type="text" name="rut" id="rut" readonly class="form-control" placeholder="Rut..." >
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <div class="form-group">
          <label>Tipo de documento</label>
          <select class="form-control" readonly name="tipo_documento" id="tipo_documento">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>
        </div>
      </div>
    </div>
    <div class="row col-lg-9 col-md-9">
      <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
        <div class="form-group">
          <label for="descripcion">Glosa</label>
          <textarea name="glosa" class="form-control" required rows="6" placeholder="Escribe aqui la glosa"></textarea>
        </div>
      </div>
    </div>
    <div class="row col-lg-9 col-md-9">
      <div class="col-lg-9 col-md-9">
      <div class="form-group">
        <a href=""><button class="btn btn-primary" type="submit">Agregar cuenta</button></a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>
    </div>
    </div>
    {!!Form::close()!!}


@endsection
