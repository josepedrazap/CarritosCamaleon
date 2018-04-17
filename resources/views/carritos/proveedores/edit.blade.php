@extends ('layouts.admin')
@section('contenido')

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Ver / Actualizar proveedor</h3>
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

    {!!Form::open(array('url'=>'carritos/proveedores/editar','method'=>'GET','autocomplete'=>'off'))!!}
    {{Form::Token()}}

    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <h4>Datos personales</h4>
        <input value="{{$proveedor->id}}" name="id" class="hidden"/>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" name="nombre" value="{{$proveedor->nombre}}" class="form-control" placeholder="nombre..." required>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
          <label for="rut">Rut</label>
          <input type="text" name="rut" class="form-control" value="{{$proveedor->rut}}" placeholder="rut..." required>
        </div>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <label for="telefono">Teléfono</label>
        <div class="input-group">
          <span class="input-group-addon">+</span>
          <input type="tel" name="telefono" class="form-control" value="{{$proveedor->telefono}}" placeholder="569..." required>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" name="email" class="form-control" value="{{$proveedor->email}}" placeholder="e-mail..." required>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
          <label for="comentarios">Descripción</label>
          <textarea name="descripcion" class="form-control" rows="6" placeholder="Escribe aquí tus comentarios">{{$proveedor->descripcion}}"</textarea>
        </div>
      </div>

    </div>

      <div class="form-group">
        <a href=""><button class="btn btn-primary" type="submit">Actualizar proveedor</button></a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>

    {!!Form::close()!!}


@endsection
