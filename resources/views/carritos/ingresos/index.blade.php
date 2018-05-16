@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Ingresos de eventos pendientes</h3>

    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Número evento</th>
            <th>Fecha evento</th>
            <th>Cliente</th>
            <th>Monto neto</th>
            <th>Iva</th>
            <th>Total</th>
            <th>Realizar ingreso</th>
          </thead>
          @foreach($data as $dat)
          <tr>
            <td># {{$dat->id}}</td>
            <td>{{$dat->fecha_hora}}</td>
            <td>{{$dat->nombre_cliente}}</td>
            <td>$ {{$dat->precio_evento - $dat->precio_evento*0.19}}</td>
            <td>$ {{$dat->precio_evento - $dat->precio_evento / 1.19}}</td>
            <td>$ {{$dat->precio_evento}}</td>
            <th>
              <a href="/carritos/ingresos/{{$dat->id}}"><button class="btn btn-info">Ingresar</button></a>
            </th>
          </tr>
          @endforeach
        </table>
      </div>

    </div>
  </div>
@endsection
