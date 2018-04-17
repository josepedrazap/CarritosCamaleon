@extends ('layouts.admin')
@section('contenido')

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Agregar cliente</h3>
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

    {!!Form::open(array('url'=>'carritos/clientes','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::Token()}}

    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <h4>Datos personales</h4>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" name="nombre" class="form-control" placeholder="nombre..." required>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
          <label for="apellido">Apellido</label>
          <input type="text" name="apellido" class="form-control" placeholder="apellido..." required>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
          <label for="rut">Rut</label>
          <input type="rut" name="rut" class="form-control" placeholder="rut..." required>
        </div>
      </div>
    </div>
      <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <label for="telefono">Teléfono</label>
        <div class="input-group">
          <span class="input-group-addon">+</span>
          <input type="tel" name="contacto" class="form-control" placeholder="569..." required>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" name="email" class="form-control" placeholder="e-mail..." required>
        </div>
      </div>
    </div>


      <div class="form-group">
        <a href=""><button class="btn btn-primary" type="submit">Agregar cliente</button></a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>

    {!!Form::close()!!}


@endsection
