@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Pagos pendientes </h3>

    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Nombre y apellido</th>
            <th>Clase</th>
            <th>Eventos pendientes</th>
            <th>Monto adeudado</th>
            <th>Opciones</th>
          </thead>
          @foreach($data as $dat)
          <tr>
            <td>{{$dat->nombre}} {{$dat->apellido}}</td>
            <td>{{$dat->clase}}</td>
            <td>{{$dat->cont}}</td>
            <td>$ {{$dat->sum}}</td>
            <td>
              <a href="/carritos/pagos/pagar/{{$dat->id}}"><button class="btn btn-success">Pagar</button></a>
              <a href="/carritos/pagos/vertodos/{{$dat->id}}"><button class="btn btn-info">Ver todos</button></a>

            </td>
          </tr>
          @endforeach
        </table>
      </div>
      {{$data->render()}}
    </div>
  </div>
@endsection
