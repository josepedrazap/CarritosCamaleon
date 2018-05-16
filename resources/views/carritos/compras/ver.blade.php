  
@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Facturas de compra número {{$factura[0]->numero_documento}}</h3>
    </div>
  </div>
  <hr></hr>

  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3">
      <div class="form-group">
        <label>Tipo de documento</label>
        <input class="form-control" value="{{$factura[0]->tipo_documento}}" readonly="readonly"/>
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3">
      <div class="form-group">
        <label>Número de documento</label>
        <input class="form-control" value="{{$factura[0]->numero_documento}}" readonly="readonly"/>
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3">
      <div class="form-group">
        <label>Fecha de documento</label>
        <input class="form-control" value="{{$factura[0]->fecha_documento}}" readonly="readonly"/>
      </div>
    </div>
  </div>
  <div class="row">

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <label for="apellido">Monto neto</label>
        <div class="input-group">
          <span class="input-group-addon">$</span>
          <input class="form-control" readonly="readonly" value="{{$factura[0]->monto_neto}}">
        </div><!-- /input-group -->
      </div><!-- /.col-lg-6 -->
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <label for="iva">IVA</label>
        <div class="input-group">
          <span class="input-group-addon">$</span>
          <input class="form-control" readonly="readonly" value="{{$factura[0]->iva}}" ></input>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <label for="total">Total</label>
        <div class="input-group">
          <span class="input-group-addon">$</span>
          <input class="form-control" readonly="readonly" value="{{$factura[0]->total}}" ></input>
        </div>
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
