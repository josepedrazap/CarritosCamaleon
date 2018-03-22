@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Clientes <a href="clientes/create"><button class="btn btn-success">Nuevo</button></a></h3>
      @include('carritos.clientes.search')
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Nombre y apellido</th>
            <th>Rut</th>
            <th>Contacto</th>
            <th>E-mail</th>
            <th>Opciones</th>
          </thead>
          @foreach($clientes as $cli)
          <tr>
            <td>{{$cli->nombre}} {{$cli->apellido}}</td>
            <td>{{$cli->rut}}</td>
            <td>{{$cli->contacto}}</td>
            <td>{{$cli->mail}}</td>
            <td>
              <a href="" data-target="#modal-delete-{{$cli->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
            </td>
          </tr>
          @include('carritos.clientes.modal')
          @endforeach
        </table>
      </div>
      {{$clientes->render()}}
    </div>
  </div>
@endsection
