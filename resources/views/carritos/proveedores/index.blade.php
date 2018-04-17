@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Proveedores <a href="proveedores/create"><button class="btn btn-success">Nuevo</button></a></h3>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Nombre</th>
            <th>Rut</th>
            <th>E-mail</th>
            <th>teléfono</th>
            <th>Descripción</th>
            <th>Opciones</th>
          </thead>
          @foreach($proveedores as $prov)
          <tr>
            <td>{{$prov->nombre}}</td>
            <td>{{$prov->rut}}</td>
            <td>{{$prov->email}}</td>
            <td>{{$prov->telefono}}</td>
            <td>{{$prov->descripcion}}</td>
            <td>
              <a href="/carritos/proveedores/{{$prov->id}}/edit"><button class="btn btn-success">Ver / Editar</button></a>

            </td>
          </tr>

          @endforeach
        </table>
      </div>
      {{$proveedores->render()}}
    </div>
  </div>
@endsection
