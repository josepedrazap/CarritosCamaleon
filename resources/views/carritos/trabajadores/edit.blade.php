@extends ('layouts.admin')
@section('contenido')

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Ver / Actualizar trabajador</h3>
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

{!! Form::open(array('url'=>'carritos/trabajadores/editar', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search'))!!}

    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <h4>Datos personales</h4>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <h4>Datos bancarios y comentarios</h4>
        <input class="hidden" value="{{$id}}" name="id"/>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="nombre_trabajador">Nombre</label>
          <input type="text" name="nombre" value="{{$trabajador[0]->nombre}}" class="form-control" placeholder="nombre...">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="apellido_trabajador">Apellido</label>
          <input type="text" name="apellido" value="{{$trabajador[0]->apellido}}" class="form-control" placeholder="dirección...">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="banco_trabajador">Banco</label>
          <select  class="form-control" name="banco">
            @foreach($bancos as $ban)
            @if($ban->valor == $trabajador[0]->banco)
            <option selected="selected" value="{{$ban->valor}}">{{$ban->valor}}</option>
            @else
            <option value="{{$ban->valor}}">{{$ban->valor}}</option>
            @endif
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="tipo_cuenta">Tipo cuenta</label>
          <select  class="form-control" name="tipo_cuenta">
            @foreach($cuentas as $ct)
            @if($ct->valor == $trabajador[0]->tipo_cuenta)
            <option selected="selected" value="{{$ct->valor}}">{{$ct->valor}}</option>
            @else
            <option value="{{$ct->valor}}">{{$ct->valor}}</option>
            @endif
            @endforeach
          </select>
        </div>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="telefono_trabajador">Teléfono</label>
          <input type="tel" value="{{$trabajador[0]->telefono}}" name="telefono" class="form-control" placeholder="+569...">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="email_trabajador">E-mail</label>
          <input type="email" name="email" value="{{$trabajador[0]->email}}" class="form-control" placeholder="e-mail...">
        </div>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="numero_cuenta">Número de cuenta</label>
          <input type="number" name="cuenta" value="{{$trabajador[0]->cuenta}}" class="form-control" placeholder="número de cuenta">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="rut">Rut</label>
          <input type="number" name="rut" value="{{$trabajador[0]->rut}}" class="form-control" placeholder="Rut">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="manejo">Apto para manejo?</label>
          <select  class="form-control" name="maneja">
            @if($trabajador[0]->maneja == 0)
            <option value="0" selected="selected">no</option>
            <option value="1" >si</option>
            @else
            <option value="1" selected="selected">si</option>
            <option value="0" >no</option>
            @endif
          </select>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="form-group">
          <label for="clase">Clase de trabajador</label>
          <select  class="form-control" name="clase">
            @if($trabajador[0]->clase == "Cocinero part-time")
            <option value="Cocinero part-time">Cocinero part-time</option>
            <option value="Interno con contrato">Interno con contrato</option>
            @else
            <option value="Cocinero part-time">Cocinero part-time</option>
            <option value="Interno con contrato">Interno con contrato</option>
            @endif

          </select>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
          <label for="comentarios">Comentarios</label>
          <textarea name="descripcion" class="form-control" rows="6" placeholder="Escribe aquí tus comentarios">{{$trabajador[0]->descripcion}}</textarea>
        </div>
      </div>

    </div>

      <div class="form-group">
        <a href=""><button class="btn btn-primary" type="submit">Actualizar trabajador</button></a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>

    {!!Form::close()!!}


@endsection
