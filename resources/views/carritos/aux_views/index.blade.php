@extends ('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-lg-9 col-md-9 col-sm-6">
    <h3>Usuarios del sistema
      <a href="registrar"><button class="btn btn-success">Registrar usuario</button></a>
      <a href="carritos/reset/create"><button class="btn btn-info">Cambiar contraseña usuario</button></a>
    </h3>

  </div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#ABEBC6">

          <th>Nombre</th>
          <th>E-mail</th>
          <th>Nivel de acceso</th>

        </thead>
        @foreach($data as $dat)
        <tr>

          <td>{{$dat->name}}</td>
          <td>{{$dat->email}}</td>
          <td>{{$dat->nivel}}</td>

        </tr>

        @endforeach
      </table>
    </div>
  </div>
</div>
@endsection
