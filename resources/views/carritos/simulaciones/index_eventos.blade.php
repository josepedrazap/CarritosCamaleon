@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Lista de eventos <a href="/simulador"><button class="btn btn-success">Crear evento</button></a></h3>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Número evento</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Opciones</th>
          </thead>

          @foreach($eventos as $eve)
          <tr>
            <td>{{$eve->id}}</td>
            <td>{{$eve->cliente}}</td>
            <td>{{$eve->fecha_hora}}</td>
            <td>
              @if($eve->condicion == 1)
              <h5 style="color:orange">Por realizar</h5>
              @elseif($eve->condicion == 2)
              <h5 style="color:green">Realizado</h5>
              @elseif($eve->condicion == 3)
              <h5 style="color:red">Cancelado</h5>
              @endif
            </td>
            <td>
              <a href="/ver_evento?id={{$eve->id}}"><Button class="btn btn-info"/>Ver</button></a>
              <a href="/carritos/pdf/despacho_checklist?id={{$eve->id}}"><Button class="btn btn-warning"/>Guía de producción</button></a>
              <a href="/estado_evento?id={{$eve->id}}"><Button class="btn btn-primary"/>Estado</button>
            </td>
          </tr>
          @endforeach
        </table>
      </div>
      {{$eventos->render()}}

    </div>
  </div>
@endsection
