@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Libro Compras Extendido</h3>
      <hr></hr>
    </div>
  </div>
<?php $id = -1; ?>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Fecha</th>
            <th>Tipo de documento</th>
            <th>Número de documento</th>
            <th>Nombre Cuenta</th>
            <th>Debe</th>
            <th>Haber</th>
            <th>Glosa</th>
          </thead>
          @foreach($cuentas as $ct)
          @if($id != $ct->id_documento)
          <tr>
            <td style="background-color:#ABEBC6" colspan="7"></td>
          </tr>
          <tr>
            <?php
              $id = $ct->id_documento;
             ?>
            <td>{{$ct->fecha_documento}}</td>
            <td>{{$ct->tipo_documento}}</td>
            <td>{{$ct->numero_documento}}</td>
            <td>{{$ct->nombre_cuenta}}</td>
            <td>{{$ct->debe}}</td>
            <td>{{$ct->haber}}</td>
            <th>{{$ct->glosa}}</th>
          </tr>
          @else
          <tr>
            <?php
              $id = $ct->id_documento;
             ?>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$ct->nombre_cuenta}}</td>
            <td>{{$ct->debe}}</td>
            <td>{{$ct->haber}}</td>
            <th>{{$ct->glosa}}</th>
          </tr>
          @endif
          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection
