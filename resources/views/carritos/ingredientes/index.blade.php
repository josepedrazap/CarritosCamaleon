@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Todos los ingredientes <a href="ingredientes/create"><button class="btn btn-success">Nuevo</button></a></h3>
      @include('carritos.ingredientes.search')
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Nombre ingrediente</th>
            <th>Tipo</th>
            <th>Unidad</th>
            <th>Precio bruto</th>
            <th>Precio líquido</th>
            <th>Opciones</th>
          </thead>

          @foreach($ingredientes as $ingr)

          <tr>
            <td>{{$ingr->nombre}}</td>
            <td>{{$ingr->tipo}}</td>
            <td>{{$ingr->unidad}}</td>
            <th>${{$ingr->precio_bruto}}</th>
            <th>${{$ingr->precio_liquido}}</th>
            <td>
              <a href="/carritos/ingredientes/{{$ingr->id}}/edit"><button class="btn btn-info">Editar</button></a>
              <a href="" data-target="#modal-delete-{{$ingr->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
            </td>
          </tr>
          @include('carritos.ingredientes.modal')
          @endforeach
        </table>
      </div>
        {{$ingredientes->render()}}
    </div>
  </div>
@endsection
