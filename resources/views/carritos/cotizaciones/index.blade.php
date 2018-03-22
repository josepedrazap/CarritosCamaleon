@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-6">
      <h3>{{$busq}} <a href="/carritos/eventos/cotizacion"><button class="btn btn-success">Nueva cotización</button></a></h3>
      @include('carritos.cotizaciones.search')
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Estado</th>
            <th>Fecha y hora</th>
            <th>Nombre cliente</th>
            <th>Dirección</th>
            <th>Contacto</th>
            <th>Opciones</th>
          </thead>
          @foreach($eventos as $eve)
          @if($eve->condicion != 0)
          <tr>

            <td style="color:blue"> <strong>Cotización</strong></td>
            <td>{{$eve->fecha_hora}}</td>
            <td>{{$eve->nombre_cliente}}</td>
            <td>{{$eve->direccion}}</td>
            @if($eve->contacto[0] == '+' && $eve->contacto[1] == 5 && $eve->contacto[2] == 6 && $eve->contacto[3] == 9 && strlen($eve->contacto) >= 11)
              <td><IMG SRC="{{ asset('img/img_wh.png') }}" WIDTH=20 HEIGHT=20>    {{$eve->contacto}}</td>
            @else
              <td><IMG SRC="{{ asset('img/img_tel.png') }}" WIDTH=20 HEIGHT=20>    {{$eve->contacto}}</td>
            @endif
            <td>
                <a href="/carritos/eventos/{{$eve->id}}"><button class="btn btn-info">ver</button></a>
                <a href="/carritos/utilidad_costos/aprobar_2/{{$eve->id}}"><button class="btn btn-info">ver valores</button></a>
                <a href="" data-target="#modal-delete-{{$eve->id}}" data-toggle="modal"><button class="btn btn-danger">Borrar</button></a>
            </td>
          </tr>
          @include('carritos.cotizaciones.modal')
          @endif
          @endforeach
        </table>
      </div>
      @if($tipo == 3)
      {{$eventos->render()}}
      @endif
    </div>
  </div>
@endsection
