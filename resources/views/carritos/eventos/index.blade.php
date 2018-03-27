@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-12">
      <h3>{{$busq}} <a href="eventos/create"><button class="btn btn-success">Nuevo evento</button></a> <a href="/carritos/eventos/cotizacion"><button class="btn btn-success">Nueva cotización</button></a></h3>
      @include('carritos.eventos.search')

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
            @if($eve->condicion == 1)
            <td style="color:orange"> <strong>Pendiente</strong></td>
            @endif
            @if($eve->condicion == 2)
            <td style="color:green"> <strong>Despachado</strong></td>
            @endif
            @if($eve->condicion == 3)
            <td style="color:grey"> <strong>Ejecutado</strong></td>
            @endif
            <td>{{$eve->fecha_hora}}</td>
            <td>{{$eve->nombre_cliente}}</td>
            <td>{{$eve->direccion}}</td>
            @if($eve->contacto[0] == 5 && $eve->contacto[1] == 6 && $eve->contacto[2] == 9 && strlen($eve->contacto) >= 11)
              <td>
                <a href="{{"https://api.whatsapp.com/send?phone=$eve->contacto"}}" target="_blank">
                  <IMG SRC="{{ asset('img/img_wh.png') }}" WIDTH=20 HEIGHT=20>    +{{$eve->contacto}}
                </a>
              </td>
            @else
              <td><IMG SRC="{{ asset('img/img_tel.png') }}" WIDTH=20 HEIGHT=20>    {{$eve->contacto}}</td>
            @endif
            <td>
              @if($eve->condicion == 1)
                <a href="/carritos/despacho/create?id={{$eve->id}}"><button class="btn btn-success">Despachar</button></a>

              @endif
              @if($eve->condicion == 2 || $eve->condicion == 3)
                <a href=""><button class="btn btn-s">Despachar</button></a>
              @endif
              @if($eve->condicion != 1)
                <a href="/carritos/eventos/{{$eve->id}}"><button class="btn btn-info">Ver</button></a>
              @else
                <a><button class="btn btn-s">Ver</button></a>
              @endif
              @if($eve->condicion == 3)
                <a href=""><button class="btn btn-succes">Cancelar</button></a>
              @else
                <a href="" data-target="#modal-delete-{{$eve->id}}" data-toggle="modal"><button class="btn btn-danger">Cancelar</button></a>
              @endif
            </td>
          </tr>
          @include('carritos.eventos.modal')
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
