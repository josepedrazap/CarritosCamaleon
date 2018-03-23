
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-6">
        <h3>Balance General correspondiente al período {{$date_1}} a {{$date_2}}</h3>
    </div>
  </div>
  <?php
      $total_debe_1 = 0;
      $total_haber_1 = 0;
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
            <th>Detalle</th>
            <th></th>
            <th></th>
            <th colspan="2">Saldos</th>
            <th colspan="2">Inventario</th>
            <th colspan="2">Resultados</th>
          </tr>
          <tr style="background-color:#ABEBC6">
            <th>Cuentas</th>
            <th>Débitos</th>
            <th>Créditos</th>
            <th>Deudor</th>
            <th>Acreedor</th>
            <th>Activo</th>
            <th>Pasivo</th>
            <th>Pérdidas</th>
            <th>Ganancias</th>
          </tr>

          @foreach($data as $dat)
          <tr>
            <td>{{$dat->nombre_cuenta}}</td>
            <td>{{$dat->debe}}</td>
            <td>{{$dat->haber}}</td>

            <?php
              $total_1 = $dat->debe - $dat->haber;
            ?>
            @if($total_1 > 0)
              <td>{{$total_1}}</td>
              <td></td>
              <?php $total_debe_1 += $total_1; ?>
            @elseif($total_1 < 0)
              <td></td>
              <td>{{$total_1*(-1)}}</td>
              <?php $total_haber_1 += $total_1*(-1); ?>
            @else
              <td>0</td>
              <td>0</td>
            @endif

            @if($dat->tipo == 'ACTIVO' || $dat->tipo == 'PASIVO')
            <?php
              $total_2 = $dat->debe - $dat->haber;
            ?>
            @if($total_2 > 0)
              <td>{{$total_2}}</td>
              <td></td>
              <?php $total_debe_2 += $total_2; ?>
            @elseif($total_2 < 0)
              <td></td>
              <td>{{$total_2*(-1)}}</td>
              <?php $total_haber_2 += $total_2*(-1); ?>
            @else
              <td>0</td>
              <td>0</td>
            @endif
            @else
            <td></td>
            <td></td>
            @endif

            @if($dat->tipo == 'GANANCIA' || $dat->tipo == 'PERDIDA')
            <?php
              $total_3 = $dat->debe - $dat->haber;
            ?>
            @if($total_3 > 0)
              <td>{{$total_3}}</td>
              <td></td>
              <?php $total_debe_3 += $total_3; ?>
            @elseif($total_3 < 0)
              <td></td>
              <td>{{$total_3*(-1)}}</td>
              <?php $total_haber_3 += $total_3*(-1); ?>
            @else
              <td>0</td>
              <td>0</td>
            @endif
            @else
            <td></td>
            <td></td>
            @endif

          </tr>
          @endforeach

        <tr>
            <th>Totales</th>
            <th>{{$total_debe}}</th>
            <th>{{$total_haber}}</th>
            <th>{{$total_debe_1}}</th>
            <th>{{$total_haber_1}}</th>

            <th>{{$total_debe_2}}</th>
            <th>{{$total_haber_2}}</th>
            <th>{{$total_debe_3}}</th>
            <th>{{$total_haber_3}}</th>
        </tr>
        <tr>
          <th>Utilidad del ejercicio</th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th>{{$total_debe_2 - $total_haber_2}}</th>
          <th>{{$total_haber_3 - $total_debe_3}}</th>
          <th></th>
        </tr>
        <tr>
          <th>Totales iguales</th>
          <th>{{$total_debe}}</th>
          <th>{{$total_haber}}</th>
          <th>{{$total_debe_1}}</th>
          <th>{{$total_haber_1}}</th>
          <th>{{$total_debe_2}}</th>
          <th>{{$total_debe_2}}</th>
          <th>{{$total_haber_3}}</th>
          <th>{{$total_haber_3}}</th>
        </tr>
        </table>
      </div>
    </div>
  </div>
