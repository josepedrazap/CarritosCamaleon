@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Extras <a href="extras/create"><button class="btn btn-success">Nuevo</button></a></h3>

    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Nombre</th>
            <th>Opciones</th>
          </thead>
          @foreach($extras as $ext)
          <tr>
            <td>{{$ext->valor}}</td>
            <td>
              <a href="" data-target="#modal-delete-{{$ext->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
            </td>
          </tr>
          @include('carritos.extras.modal')
          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection
