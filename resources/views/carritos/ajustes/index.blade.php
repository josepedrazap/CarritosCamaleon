@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Ajustes <a href="ajustes/create"><button class="btn btn-success">Nueva</button></a></h3>
      @include('carritos.ajustes.search2')
    </div>
  </div>
  <div class="list-group">
      <a href="/carritos/excel/index_excel_compras/{{$date_1}}/{{$date_2}}" class="list-group-item list-group-item-success">
        <strong>Presione aquí para obtener los ajustes en Excel (No implementado aun)</strong>
      </a>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Serie de comprobante</th>
            <th>Fecha de comprobante</th>
            <th>Opciones</th>
          </thead>
          @foreach($facturas as $fac)
          <tr>
            <td># {{$fac->id + 1500}}</td>
            <td>{{$fac->fecha_ingreso}}</td>

            <th>
              <a href="/carritos/ajustes/{{$fac->id}}"><button class="btn btn-info">Ver</button></a>
            </th>
          </tr>
          @endforeach
        </table>
      </div>
      {{$facturas->render()}}
    </div>
  </div>
@endsection
