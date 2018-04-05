@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>id evento</th>
            <th>Estado</th>
            <th>Fecha y hora</th>
            <th>Dirección</th>
            <th>Contacto</th>
            <th>Detalles</th>
          </thead>
          @foreach($eventos as $eve)
          @if($eve->condicion != 0)
          <tr>
            <td>{{$eve->id}}</td>
            <td style="color:blue"> <strong>Cotización</strong></td>
            <td>{{$eve->fecha_hora}}</td>
            <td>{{$eve->direccion}}</td>
            @if($eve->contacto[0] == '+' && $eve->contacto[1] == 5 && $eve->contacto[2] == 6 && $eve->contacto[3] == 9 && strlen($eve->contacto) >= 11)
              <td><IMG SRC="{{ asset('img/img_wh.png') }}" WIDTH=20 HEIGHT=20>    {{$eve->contacto}}</td>
            @else
              <td><IMG SRC="{{ asset('img/img_tel.png') }}" WIDTH=20 HEIGHT=20>    {{$eve->contacto}}</td>
            @endif
            <td>
                <a href="/carritos/eventos/{{$eve->id}}"><button class="btn btn-info">ver</button></a>
            </td>
          </tr>
          @endif
          @endforeach
        </table>
      </div>

    </div>
  </div>
@endsection
