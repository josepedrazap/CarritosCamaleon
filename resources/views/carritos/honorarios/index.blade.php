@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-6">
      <h3>Libro de honorarios <a href="honorarios/create"><button class="btn btn-success">Ingresar honorario</button></a></h3>
      @include('carritos.honorarios.search2')
    </div>
  </div>
  <div class="list-group">
      <a href="/carritos/excel/index_excel/{{$date_1}}/{{$date_2}}" class="list-group-item list-group-item-success">
        <strong>Presione aquí para obtener los honorarios en Excel</strong>
      </a>
  </div>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <tr style="background-color:#ABEBC6">
            <th>Fecha</th>
            <th>Nombre</th>
            <th>Rut</th>
            <th>Tipo documento</th>
            <th>Número documento</th>
            <th>Monto</th>
            <th>Retención</th>
            <th>Monto final</th>


          </tr>
          @foreach($data as $dat)
          <tr>
            <td>{{$dat->fecha_documento}}</td>
            <td>{{$dat->nombre}} {{$dat->apellido}}</td>
            <th>{{$dat->rut}}</th>
            <td>{{$dat->tipo_documento}}</td>
            <td>{{$dat->numero_documento}}</td>
            <td>$ {{$dat->monto_neto}}</td>
            <td>$ {{$dat->iva}}</td>
            <td>$ {{$dat->total}}</td>

          </tr>

          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection
