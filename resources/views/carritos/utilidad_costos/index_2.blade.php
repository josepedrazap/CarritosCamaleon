@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-6">
      <h3></h3>

    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Estado</th>
            <th>Fecha y hora</th>
            <th>Nombre cliente</th>
            <th>Dirección</th>
            <th>Precio del evento</th>
            <th>Costo del evento</th>
            <th>Productos</th>
            <th>Ver utilidad</th>
          </thead>
          @foreach($eventos as $eve)
          @if($id != $eve->id)
          <?php $id = $eve->id ?>
          @if($eve->condicion != 0)
          <tr>
            @if($eve->condicion == 1)
            <td style="color:orange"> <strong>Pendiente</strong></td>
            @endif
            @if($eve->condicion == 2)
            <td style="color:green"> <strong>Despachado</strong></td>
            @endif
            @if($eve->condicion == 3)
            <td style="color:grey"> <strong>Ejecutado</strong></td>
            @endif
            <td>{{$eve->fecha_hora}}</td>
            <td>{{$eve->nombre_cliente}}</td>
            <td>{{$eve->direccion}}</td>
            <td>${{$eve->precio_evento}}</td>
            <td>${{$eve->costo_final}}</td>
            <td>
              <ul class="list-group">
                @foreach($eventos as $eve)
                @if($eve->id == $id)
                <li>{{$eve->cantidad}} {{$eve->nombre}} a ${{$eve->precio_a_cobrar}}</li>
                @endif
                @endforeach
              </ul>
            </td>
            <td><a href="/carritos/utilidad_costos/aprobar_2/{{$id}}"><button class="btn btn-info">ver</button></a></td>
          </tr>
          @include('carritos.eventos.modal')
          @endif
          @endif
          @endforeach
        </table>
      </div>

      {{$eventos->render()}}

    </div>
  </div>
@endsection
