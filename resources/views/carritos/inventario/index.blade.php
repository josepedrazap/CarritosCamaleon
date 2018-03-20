@extends ('layouts.admin')
@section('contenido')
  <div class="row">
    <div class="col-lg-5 col-md-5 col-sm-5">
      <h3>Inventario <a href="inventario/create"><button class="btn btn-success">Agregar inventario</button></a></h3>
      @include('carritos.inventario.search')
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#ABEBC6">
            <th>Id</td>
            <th>Item</th>
            <th>Cantidad</th>
            <th>Tipo</th>
          </thead>
          @foreach($data as $dat)
          <tr>
            <td>{{$dat->id_item}}</td>
            <td>{{$dat->nombre}}</td>
            <td>{{$dat->cantidad }} {{$dat->unidad }}</td>
            <td>{{$dat->tipo}}</td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection
