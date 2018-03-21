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
  <?php
      $total_debe_2 = 0;
      $total_haber_2 = 0;
      $total_debe_3 = 0;
      $total_haber_3 = 0;
   ?>
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

          @foreach($data as $dat)
          <tr>
            <td>{{$dat->nombre_cuenta}}</td>
            <td>{{$dat->debe}}</td>
            <td>{{$dat->haber}}</td>
            @if($dat->tipo == 'ACTIVO' || $dat->tipo == 'PASIVO')
            <?php
              $total = $dat->debe - $dat->haber;
            ?>
            @if($total > 0)
              <td>{{$total}}</td>
              <td>0</td>
              <?php $total_debe_2 += $total; ?>
            @elseif($total < 0)
              <td>0</td>
              <td>{{$total*(-1)}}</td>
              <?php $total_haber_2 += $total*(-1); ?>
            @else
              <td>0</td>
              <td>0</td>
            @endif
            @endif

            @if($dat->tipo == 'GASTOS' || $dat->tipo == 'COSTOS')
            <?php
              $total = $dat->debe - $dat->haber;
            ?>
            @if($total > 0)
              <td>{{$total}}</td>
              <td>0</td>
              <?php $total_debe_3 += $total; ?>
            @elseif($total < 0)
              <td>0</td>
              <td>{{$total*(-1)}}</td>
              <?php $total_haber_2 += $total*(-1); ?>
            @else
              <td>0</td>
              <td>0</td>
            @endif
            @endif
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
        <tfoot>
          <th>Sub Totales</th>
          <th>{{$total_debe}}</th>
          <th>{{$total_haber}}</th>
          <th>{{$total_debe_2}}</th>
          <th>{{$total_haber_2}}</th>
          <th>{{$total_debe_3}}</th>
          <th>{{$total_haber_3}}</th>
          <th></th>
          <th></th>
        </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
