@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-6">
      <h3>Ingresos por eventos</h3>
      @include('carritos.utilidad_costos.search')
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <hr></hr>
      @if($ingreso_eventos > 0)
      <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <label for="Utilidad final">Valor total eventos</label>
        <div class="input-group">
          <span class="input-group-addon">$</span>
          <input class="form-control" readonly="readonly" name="valor_final" value="{{$ingreso_eventos}}">
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <label for="Utilidad final">Costo total eventos</label>
        <div class="input-group">
          <span class="input-group-addon">$</span>
          <input class="form-control" readonly="readonly" value="{{$costo_eventos}}">
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <label for="Utilidad final">Iva total eventos</label>
        <div class="input-group">
          <span class="input-group-addon">$</span>
          <input class="form-control" readonly="readonly"  value="{{$iva_eventos}}">
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <label for="Utilidad final">Utilidad total eventos</label>
        <div class="input-group">
          <span class="input-group-addon">$</span>
          <input class="form-control" readonly="readonly"  value="{{$utilidad_eventos}}">
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <label for="Utilidad final">Porcentaje utilidad total</label>
        <div class="input-group">
          <span class="input-group-addon">%</span>
          <input class="form-control" readonly="readonly"  value="{{round(($utilidad_eventos/$ingreso_eventos)*100,2)}}">
        </div>
      </div>
      @endif
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12">
        <hr></hr>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Fecha</th>
            <th>Estado</th>
            <th>Valor</th>
            <th>Costo</th>
            <th>Iva ajustado</th>
            <th>Porcentaje ganancia</th>
            <th>Utilidad</th>
          </thead>
          @foreach($data as $dat)
          <tr>
            <td>{{$dat->fecha_hora}}</td>

            @if($dat->condicion == 1)
            <td style="color:orange"> <strong>Pendiente</strong></td>
            @endif
            @if($dat->condicion == 2)
            <td style="color:green"> <strong>Despachado</strong></td>
            @endif
            @if($dat->condicion == 3)
            <td style="color:grey"> <strong>Ejecutado</strong></td>
            @endif

            <td>$ {{$dat->precio_evento}}</td>
            <td>$ {{$dat->costo_final}}</td>
            <td>$ {{$dat->iva_por_pagar - $dat->total_ingredientes_iva}}</td>
            <td>% {{round(($dat->utilidad_final / $dat->precio_evento)*100, 2)}}</td>
            <td>$ {{$dat->utilidad_final}}</td>
          </tr>
          @include('carritos.gastos.modal')
          @endforeach
        </table>
      </div>
    </div>

  </div>
@endsection
