@extends ('layouts.admin')
@section('contenido')

  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Comprobantes <a href="ajustes/create"><button class="btn btn-success">Nuevo</button></a></h3>
      @include('carritos.ajustes.search2')
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Número de comprobante</th>
            <th>Fecha de ingreso comprobante</th>
            <th>Opciones</th>
          </thead>
          @foreach($facturas as $fac)
          <tr>
            <td># {{$fac->numero_comprobante}}</td>
            <td>{{$fac->fecha_ingreso}}</td>
            <th>
              <a href="/carritos/ajustes/{{$fac->id}}"><button class="btn btn-info">Ver / Editar </button></a>
            </th>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection
