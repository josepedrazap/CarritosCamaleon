@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-6">
      <h3>Balance</h3>
    </div>
  </div>
  <div class="list-group">
      <a href="/carritos/pdf/balance/" class="list-group-item list-group-item-success">
        Todos los eventos del período están aprobados. Puede generar el balance correctamente.
        <strong>Presione aquí para obtener el balance en PDF</strong>
      </a>
  </div>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <tr style="background-color:#ABEBC6">
            <th>Cuenta</th>
            <th>Debe</th>
            <th>Haber</th>
            <th>Debe</th>
            <th>Haber</th>
            <th>Debe</th>
            <th>Haber</th>
            <th>Debe</th>
            <th>Haber</th>
          </tr>
          <tfoot>
            <th>Sub Totales</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
          </tfoot>
          @foreach($data as $dat)
          <tr>
            <td>{{$dat->nombre_cuenta}}</td>
            <td>{{$dat->debe}}</td>
            <td>{{$dat->haber}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          @endforeach
        <tr>
          <th>Totales Generales</th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
        </table>
      </div>
    </div>
  </div>
@endsection
