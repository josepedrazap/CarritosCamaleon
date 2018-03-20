@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Eventos despachados</h3>
      @include('carritos.despacho.search')
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
          <tr>
            @if($eve->condicion == 1)
            <td style="color:orange"> <strong>Pendiente</strong></td>
            @endif
            @if($eve->condicion == 2)
            <td style="color:green" ><strong>Despachado</strong></td>
            @endif
            @if($eve->condicion == 3)
            <td style="color:grey"> <strong>Ejecutado</strong></td>
            @endif
            <td>{{$eve->fecha_hora}}</td>
            <td>{{$eve->nombre_cliente}}</td>
            <td>{{$eve->direccion}}</td>
            @if($eve->contacto[0] == '+' && $eve->contacto[1] == 5 && $eve->contacto[2] == 6 && $eve->contacto[3] == 9 && strlen($eve->contacto) >= 11)
              <td><IMG SRC="{{ asset('img/img_wh.png') }}" WIDTH=20 HEIGHT=20>    {{$eve->contacto}}</td>
            @else
              <td><IMG SRC="{{ asset('img/img_tel.png') }}" WIDTH=20 HEIGHT=20>    {{$eve->contacto}}</td>
            @endif
            <td>
                <a href="/carritos/pdf/despacho_checklist/{{$eve->id}}"><button class="btn btn-primary">Checklist</button></a>
                @if($eve->condicion != 1)
                <a href="/carritos/eventos/{{$eve->id}}"><button class="btn btn-info">Ver</button></a>
                @endif
                <a href="" data-target="#modal-delete-{{$eve->id}}" data-toggle="modal"><button class="btn btn-dange">Cancelar</button></a>
            </td>
          </tr>
          @include('carritos.eventos.modal')
          @endforeach
        </table>
      </div>

      {{$eventos->render()}}

    </div>
  </div>
@endsection
