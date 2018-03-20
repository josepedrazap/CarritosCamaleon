@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Vehículos <a href="extras/create_vehiculo"><button class="btn btn-success">Nuevo</button></a></h3>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Alias</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Patente</th>
            <th>Año</th>
            <th>Opciones</th>
          </thead>
          @foreach($vehiculos as $veh)
          <tr>
            <td>{{$veh->alias}}</td>
            <td>{{$veh->marca}}</td>
            <td>{{$veh->modelo}}</td>
            <td>{{$veh->patente}}</td>
            <td>{{$veh->año}}</td>
            <td>
              <a href="" data-target="#modal-delete-{{$veh->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
            </td>
          </tr>
          @include('carritos.extras.modal_vehiculos')
          @endforeach
        </table>
      </div>

    </div>
  </div>
@endsection
