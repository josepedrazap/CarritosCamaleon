@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Libro de Compras <a href="compras/create"><button class="btn btn-success">Nueva</button></a></h3>
      @include('carritos.compras.search2')
    </div>
  </div>
  <div class="list-group">
      <a href="/carritos/excel/index_excel_compras/{{$date_1}}/{{$date_2}}" class="list-group-item list-group-item-success">
        <strong>Presione aquí para obtener las compras en Excel</strong>
      </a>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Número documento</th>
            <th>Fecha de documento</th>
            <th>Tipo documento</th>
            <th>Rut proveedor</th>
            <th>Neto</th>
            <th>Iva</th>
            <th>Total</th>
            <th>Ver</th>
          </thead>
          @foreach($facturas as $fac)
          <tr>
            @if( ($fac->monto_neto + $fac->iva) != $fac->total)
            <td style="background-color:#F5A9A9"># {{$fac->numero_documento}}</td>
            @else
            <td style="background-color:#BCF5A9"># {{$fac->numero_documento}}</td>
            @endif
            <td>{{$fac->fecha_documento}}</td>
            <td>{{$fac->tipo_documento}}</td>
            <td>{{$fac->rut}}</td>

            @if(($fac->monto_neto + $fac->iva) != $fac->total)
            <td style="background-color:#F5A9A9">$ {{$fac->monto_neto}}</td>
            <td style="background-color:#F5A9A9">$ {{$fac->iva}}</td>
            <td style="background-color:#F5A9A9">$ {{$fac->total}}</td>
            @else
            <td style="background-color:#BCF5A9">$ {{$fac->monto_neto}}</td>
            <td style="background-color:#BCF5A9">$ {{$fac->iva}}</td>
            <td style="background-color:#BCF5A9">$ {{$fac->total}}</td>
            @endif
            <th>
              <a href="/carritos/compras/{{$fac->id}}"><button class="btn btn-info">Ver</button></a>
            </th>
          </tr>
          @endforeach
        </table>
      </div>

    </div>
  </div>
@endsection
