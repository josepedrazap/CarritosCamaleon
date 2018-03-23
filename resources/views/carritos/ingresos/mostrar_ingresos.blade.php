@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Ingresos por eventos</h3>

    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Número evento</th>
            <th>Fecha documento</th>
            <th>Número documento</th>
            <th>Tipo documento</th>
            <th>Rut tercero</th>
            <th>Monto neto</th>
            <th>Iva</th>
            <th>Total</th>
            <th>Mostrar información</th>

          </thead>
          @foreach($data as $dat)
          <tr>
            <td># {{$dat->id_eve}}</td>
            <td>{{$dat->fecha_documento}}</td>
            <td>{{$dat->numero_documento}}</td>
            <td>{{$dat->tipo_documento}}</td>
            <td>{{$dat->rut}}</td>
            <th>$ {{$dat->monto_neto}}</th>
            <th>$ {{$dat->iva}}</th>
            <th>$ {{$dat->total}}</th>
            <th>
              <a href="/carritos/ingresos/show_2/{{$dat->id_doc}}"><button class="btn btn-info">Ver</button></a>
              <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Información <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><h5>Nombre cliente: {{$dat->nombre}} {{$dat->apellido}}</h5></li>
                    <li><h5>Fecha evento: {{$dat->fecha_hora}}</h5></li>
                    <li><h5>Dirección evento: {{$dat->direccion}}</h5></li>
                  </ul>
              </div>
            </th>
          </tr>
          @endforeach
        </table>
      </div>

    </div>
  </div>
@endsection
