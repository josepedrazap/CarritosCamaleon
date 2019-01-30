@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Simulaciones <a href="/simulador"><button class="btn btn-success">Nueva</button></a></h3>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Descripción</th>
            <th>Opciones</th>
          </thead>
          @foreach($simulaciones as $sim)
          <tr>
            <td>{{$sim->nombre}}</td>
            <td>{{$sim->fecha}}</td>
            <td>{{$sim->descripcion}}</td>
            <td>
              <a href="/editar_simulacion?id={{$sim->id}}"><Button class="btn btn-info"/>Ver / Editar</button></a>
              <a href="/simulacion_resumen?id={{$sim->id}}"><Button class="btn btn-primary"/>Generar como evento</button></a>
              <a href="" data-target="#modal-delete-{{$sim->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
            </td>
          </tr>
          @include('carritos.simulaciones.modal_delete_sim')

          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection
