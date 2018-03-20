@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Trabajadores <a href="trabajadores/create"><button class="btn btn-success">Nuevo</button></a></h3>
      @include('carritos.trabajadores.search')
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Nombre y apellido</th>
            <th>Clase</th>
            <th>Contacto</th>
            <th>Maneja</th>
            <th>Eventos trabajados</th>
            <th>Opciones</th>
          </thead>
          @foreach($trabajadores as $tra)
          <tr>
            <td>{{$tra->nombre}} {{$tra->apellido}}</td>
            <td>{{$tra->clase}}</td>
            <td>{{$tra->telefono}}</td>
            @if($tra->maneja)
            <td>Si</td>
            @else
            <td>No</td>
            @endif
            <td></td>
            <td>
              <a href="/carritos/productos/"><button class="btn btn-info">Editar</button></a>
              <a href="/carritos/pagos/vertodos/{{$tra->id}}"><button class="btn btn-info">Ver pagos</button></a>
              <a href="" data-target="#modal-delete-{{$tra->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
            </td>
          </tr>
          @include('carritos.trabajadores.modal')
          @endforeach
        </table>
      </div>
      {{$trabajadores->render()}}
    </div>
  </div>
@endsection
