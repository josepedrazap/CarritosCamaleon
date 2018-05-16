
@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Ajuste comprobante número {{$id + 1500}}</h3>
      <h4>{{$fecha_ingreso}}</h4>
    </div>
  </div>
  <hr></hr>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Prefijo</th>
            <th>Nombre cuenta</th>
            <th>Debe</th>
            <th>Haber</th>
            <th>Glosa</th>
          </thead>
          @foreach($cuentas as $cuenta)
          <tr>
            <td>{{$cuenta->prefijo}}</td>
            <td>{{$cuenta->nombre_cuenta}}</td>
            <td>$ {{$cuenta->debe}}</td>
            <td>$ {{$cuenta->haber}}</td>
            <td>{{$cuenta->glosa}}</td>
          </tr>
          @endforeach
        </table>
      </div>

    </div>
  </div>
@endsection
