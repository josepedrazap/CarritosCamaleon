@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Gastos</h3>

    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Número documento</th>
            <th>Número comprobante</th>
            <th>Fecha de documento</th>
            <th>Tipo documento</th>
            <th>Rut proveedor</th>
            <th>Neto</th>
            <th>Iva</th>
            <th>Total</th>
            <th>Ver</th>
          </thead>

        </table>
      </div>

    </div>
  </div>
@endsection
