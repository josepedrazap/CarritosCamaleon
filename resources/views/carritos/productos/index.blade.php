@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8">
      <h3>Ver productos <a href="productos/create"><button class="btn btn-success">Nuevo</button></a></h3>
      @include('carritos.productos.search')
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Nombre producto</th>
            <th>Precio</th>
            <th>Ingredientes</th>
            <th>Opciones</th>
          </thead>

          @foreach($productos as $prod)
          @if($id != $prod->id)

          <?php $id = $prod->id ?>

          <tr>
            <td>{{$prod->nombre}}</td>
            <td>$ {{$prod->precio}}</td>
            <td>
              <ul>
                @foreach($productos as $prod)
                @if($prod->id == $id)
                <li>{{$prod->ingrediente}} {{$prod->porcion}} {{$prod->unidad}}</li>
                @endif
                @endforeach
              </ul>
            </td>
            <td>
              <a href="" data-target="#modal-delete-{{$prod->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
            </td>
          </tr>

          @endif
            @include('carritos.productos.modal')
          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection
