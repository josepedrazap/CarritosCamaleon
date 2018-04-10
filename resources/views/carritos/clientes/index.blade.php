@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Clientes <a href="clientes/create"><button class="btn btn-success">Nuevo</button></a></h3>
      @include('carritos.clientes.search')
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Nombre y apellido</th>
            <th>Rut</th>
            <th>Contacto</th>
            <th>E-mail</th>
            <th>Opciones</th>
          </thead>
          @foreach($clientes as $cli)
          <tr>
            <td>{{$cli->nombre}} {{$cli->apellido}}</td>
            <td>{{$cli->rut}}</td>
            @if($cli->contacto[0] == 5 && $cli->contacto[1] == 6 && $cli->contacto[2] == 9 && strlen($cli->contacto) >= 11)
              <td>
                <a href="{{"https://api.whatsapp.com/send?phone=$cli->contacto"}}" target="_blank">
                  <IMG SRC="{{ asset('img/img_wh.png') }}" WIDTH=20 HEIGHT=20>    +{{$cli->contacto}}
                </a>
              </td>
            @else
              <td><IMG SRC="{{ asset('img/img_tel.png') }}" WIDTH=20 HEIGHT=20>    {{$cli->contacto}}</td>
            @endif
            <td>{{$cli->mail}}</td>
            <td>
              <a href="/carritos/clientes/ver_eventos/{{$cli->id}}"><button class="btn btn-info">ver eventos</button></a>

              <a href="" data-target="#modal-delete-{{$cli->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
            </td>
          </tr>
          @include('carritos.clientes.modal')
          @endforeach
        </table>
      </div>
      {{$clientes->render()}}
    </div>
  </div>
@endsection
