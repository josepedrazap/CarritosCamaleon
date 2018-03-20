@extends ('layouts.admin')
@section('contenido')

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <h3>Ver evento cliente {{$evento->nombre_cliente}}</h3>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead>
          <th>Fecha y hora</th>
          <th>Nombre cliente</th>
          <th>Dirección</th>
          <th>Contacto</th>
        </thead>
        @foreach($evento as $eve)
        <tr>
          <td>{{$eve->fecha_hora}}</td>
          <td>{{$eve->nombre_cliente}}</td>
          <td>{{$eve->direccion}}</td>
          <td>{{$eve->contacto}}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>
@endsection
