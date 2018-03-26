@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Libro Mayor</h3>
      <hr></hr>
    </div>
  </div>
<?php $pre = ""; ?>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Fecha</th>
            <th>Nombre Cuenta</th>
            <th>Debe</th>
            <th>Haber</th>
            <th>Glosa</th>
          </thead>
          @foreach($cuentas as $ct)
          @if($pre != $ct->nombre_cuenta)
          <tr>
            <td style="background-color:#ABEBC6" colspan="5"></td>
          </tr>
          <tr>
            <td>{{$ct->fecha}}</td>
            <td>{{$ct->nombre_cuenta}}</td>
            <td>{{$ct->debe}}</td>
            <td>{{$ct->haber}}</td>
            <th>{{$ct->glosa}}</th>
          </tr>
          @else
          <tr>
            <td>{{$ct->fecha}}</td>
            <td>{{$ct->nombre_cuenta}}</td>
            <td>{{$ct->debe}}</td>
            <td>{{$ct->haber}}</td>
            <th>{{$ct->glosa}}</th>
          </tr>
          @endif
          <?php
            $pre = $ct->nombre_cuenta;
           ?>
          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection
