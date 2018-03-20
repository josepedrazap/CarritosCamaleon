@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <h3>Balance correspondiente al periodo {{$date_1}} a {{$date_2}}</h3>

      @include('carritos.gastos.search')
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
      @if($condicional > 0)
      <div class="list-group">
          <a href="/carritos/utilidad_costos" class="list-group-item list-group-item-danger">
              Atención: hay {{$condicional}} eventos sin aprobar pendientes. Presiona sobre este mensaje para ir a los eventos pendientes.
          </a>
      </div>
      @elseif($numero_eventos != 0 || $numero_gastos != 0 || $cant_pagos_cocineros != 0)
      <div class="list-group">
          <a href="/carritos/pdf/balance/{{$date_1}}/{{$date_2}}" class="list-group-item list-group-item-success">
            Todos los eventos del período están aprobados. Puede generar el balance correctamente.
            <strong>Presione aquí para obtener el balance en PDF</strong>
          </a>
      </div>
      @else
      <div class="list-group">
          <a href="" class="list-group-item list-group-item-warning">
            Seleccione un período válido.
          </a>
      </div>
      @endif
    </div>
  </div>
@if($numero_eventos != 0 || $numero_gastos != 0 || $cant_pagos_cocineros != 0)
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6">
      <hr></hr>
      <h4>Ingreso por eventos</h4>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#81BEF7">
            <th>Número de eventos </th>
            <th>Ingreso total eventos bruto (+)</th>
            <th>Iva eventos (-)</th>
          </thead>
          <tr>
            <th><input readonly="readonly" class="form-control" value="{{$numero_eventos}}"></th>
            <th><input readonly="readonly" class="form-control" value="{{$ingreso_eventos_bruto}}"></th>
            <th><input readonly="readonly" class="form-control" value="{{$iva_eventos}}"></th>
          </tr>
        </table>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
      <hr></hr>
      <h4>Gastos generales</h4>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#F1948A">
            <th>Gasto total bruto(-)</th>
            <th>Iva por cobrar (+)</th>
            <th>Gasto total líquido</th>
          </thead>
          <tr>
            <th><input readonly="readonly" class="form-control" value="{{$gastos_totales}}"></th>
            <th><input readonly="readonly" class="form-control" value="{{$iva_total_gastos}}"></th>
            <th><input readonly="readonly" class="form-control" value="{{$valor_total_gastos_liquido}}"></th>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6">
      <hr></hr>
      <h4>Gasto en eventos</h4>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#F1948A">
            <th>Gasto en ingredientes bruto (*)</th>
            <th>Gasto en extras (*)</th>
            <th>Iva ingredientes (*)</th>
          </thead>
          <tr>
            <th><input readonly="readonly" class="form-control" value="{{$gasto_eventos_bruto_ingr}}"></th>
            <th><input readonly="readonly" class="form-control" value="{{$gasto_eventos_extra}}"></th>
            <th><input readonly="readonly" class="form-control" value="{{$gasto_eventos_iva_ingr}}"></th>
          </tr>
        </table>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
      <hr></hr>
      <h4>Gasto en cocineros</h4>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#F1948A">
            <th>Número de pagos</th>
            <th>Total (-)</th>
          </thead>
          <tr>
            <th><input readonly="readonly" class="form-control" value="{{$cant_pagos_cocineros}}"></th>
            <th><input readonly="readonly" class="form-control" value="{{$pagos_cocineros}}"></th>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <hr></hr>
        <h4>Resumen</h4>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color:#F1948A">
              <th>Ingreso en ventas bruto (+)</th>
              <th>Gasto total final (-)</th>
              <th>Iva final ajustado (-)</th>
              <th>Utilidad final (=)</th>
            </thead>
            <tr>
              <th><input readonly="readonly" class="form-control" value="{{$ingreso_eventos_bruto}}"></th>
              <th><input readonly="readonly" class="form-control" value="{{$gasto_final}}"></th>
              <th><input readonly="readonly" class="form-control" value="{{$iva_final}}"></th>
              <th><input readonly="readonly" class="form-control" value="{{$utilidad_final}}"></th>
            </tr>
          </table>
        </div>
      </div>
    </div>
@endif
@endsection
