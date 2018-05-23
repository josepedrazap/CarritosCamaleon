@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Libro de Ventas</h3>
      @include('carritos.ingresos.search2')

    </div>
  </div>
  <div class="list-group">
      <a href="/carritos/excel/index_excel_ingresos/{{$date_1}}/{{$date_2}}" class="list-group-item list-group-item-success">
        <strong>Presione aquí para obtener los ingresos en Excel</strong>
      </a>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Número comprobante</th>
            <th>Fecha de ingreso</th>
            <th>Fecha documento</th>
            <th>Número documento</th>
            <th>Tipo documento</th>
            <th>Nombre tercero</th>
            <th>Rut tercero</th>
            <th>Monto neto</th>
            <th>Iva</th>
            <th>Total</th>
          </thead>
          @foreach($data as $dat)
          <tr>
            <th>{{$dat->numero_comprobante}}</th>
            <th>{{$dat->fecha_ingreso}}</th>
            <td>{{$dat->fecha_documento}}</td>
            <td>{{$dat->numero_documento}}</td>
            <td>{{$dat->tipo_documento}}</td>
            <td>{{$dat->nombre}}</td>
            <td>{{$dat->rut}}</td>
            <th>{{$dat->monto_neto}}</th>
            <th>{{$dat->iva}}</th>
            <th>{{$dat->total}}</th>
          </tr>
          @endforeach
        </table>
      </div>

    </div>
  </div>
@endsection
