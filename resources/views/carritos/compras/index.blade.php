@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Facturas de compra <a href="compras/create"><button class="btn btn-success">Nueva</button></a></h3>
    
    </div>
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
            <td># {{$fac->numero_documento}}</td>
            <td>{{$fac->fecha_documento}}</td>
            <td>{{$fac->tipo_documento}}</td>
            <td>{{$fac->rut}}</td>
            <td>$ {{$fac->monto_neto}}</td>
            <td>$ {{$fac->iva}}</td>
            <td>$ {{$fac->total}}</td>
            <th>
              <a href="/carritos/compras/{{$fac->id}}"><button class="btn btn-info">Ver</button></a>
            </th>
          </tr>
          @endforeach
        </table>
      </div>
      {{$facturas->render()}}
    </div>
  </div>
@endsection
