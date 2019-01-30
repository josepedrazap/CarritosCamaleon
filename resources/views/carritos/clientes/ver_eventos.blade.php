@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>id evento</th>
            <th>Fecha y hora</th>
            <th>Dirección</th>
            <th>Detalles</th>
          </thead>
          @foreach($eventos as $eve)
          @if($eve->condicion != 0)
          <tr>
            <td>{{$eve->id}}</td>
            <td>{{$eve->fecha_hora}}</td>
            <td>{{$eve->direccion}}</td>
            <td>
              <a href="/ver_evento?id={{$eve->id}}"><Button class="btn btn-info"/>Ver</button></a>
            </td>
          </tr>
          @endif
          @endforeach
        </table>
      </div>

    </div>
  </div>
@endsection
