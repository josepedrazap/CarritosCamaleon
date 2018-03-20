@extends ('layouts.admin')
@section('contenido')

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Agregar trabajador</h3>
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

    {!!Form::open(array('url'=>'carritos/trabajadores','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::Token()}}
    {{ csrf_field() }}
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <h4>Datos personales</h4>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <h4>Datos bancarios y comentarios</h4>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="nombre_trabajador">Nombre</label>
          <input type="text" name="nombre_trabajador" class="form-control" placeholder="nombre...">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="apellido_trabajador">Apellido</label>
          <input type="text" name="apellido_trabajador" class="form-control" placeholder="dirección...">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="banco_trabajador">Banco</label>
          <select  class="form-control" name="banco">
            <option value="Ninguno">Ninguno</option>
            <option value="Banco de Chile">Banco de Chile</option>
            <option value="Banco Santander">Banco Santander</option>
            <option value="Banco BCI">Banco BCI</option>
            <option value="Banco Estado">Banco Estado</option>
            <option value="Banco Itaú">Banco Itaú</option>
            <option value="Banco BBUVA">Banco BBVA</option>
          </select>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="tipo_cuenta">Tipo cuenta</label>
          <select  class="form-control" name="tipo_cuenta">
            <option value="ninguna">Ninguna</option>
            <option value="cuenta_corriente">cuenta corriente</option>
            <option value="cuenta_vista">cuenta vista</option>
            <option value="cuenta_ahorro">cuenta de ahorro</option>
            <option value="cuenta_rut">cuentaRut</option>
            <option value="cuenta_rut">cuenta de gastos</option>
          </select>
        </div>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="telefono_trabajador">Teléfono</label>
          <input type="tel" name="telefono_trabajador" class="form-control" placeholder="+569...">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="email_trabajador">E-mail</label>
          <input type="email" name="email_trabajador" class="form-control" placeholder="e-mail...">
        </div>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="numero_cuenta">Número de cuenta</label>
          <input type="number" name="numero_cuenta" class="form-control" placeholder="número de cuenta">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="rut">Rut</label>
          <input type="number" name="rut" class="form-control" placeholder="Rut">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="manejo">Apto para manejo?</label>
          <select  class="form-control" name="maneja">
            <option value="0">no</option>
            <option value="1">si</option>
          </select>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="clase">Clase de trabajador</label>
          <select  class="form-control" name="clase">
            <option value="Cocinero part-time">Cocinero part-time</option>
            <option value="Interno con contrato">Interno con contrato</option>
          </select>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
          <label for="comentarios">Comentarios</label>
          <textarea name="descripcion" class="form-control" rows="6" placeholder="Escribe aquí tus comentarios"></textarea>
        </div>
      </div>

    </div>

      <div class="form-group">
        <a href=""><button class="btn btn-primary" type="submit">Agregar trabajador</button></a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>

    {!!Form::close()!!}


@endsection
