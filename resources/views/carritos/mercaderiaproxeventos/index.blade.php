@extends ('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-lg-9 col-md-9 col-sm-6">
    <h3>Mercadería próximos {{$cant_eventos}} eventos entre {{$date_1}} y {{$date_2}}</h3>
    @include('carritos.mercaderiaproxeventos.search')
    <hr></hr>
  </div>
</div>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#ABEBC6">
          <th>Ingrediente</th>
          <th>Cantidad necesaria</th>
          <th>Stock en inventario</th>
        </thead>
        @foreach($ingredientes_totales as $it)
        <tr>
          <td>{{$it->nombre}}</td>
          @if($it->unidad == 'gramos')
          <td>{{$it->sum/1000}} Kg</td>
          @else
          <td>{{round($it->sum)}} {{$it->unidad}}</td>
          @endif
          @if($it->inventareable == 1)
          <td>{{$it->stock}}</td>
          @else
          <td>No aplica inventario</td>
          @endif
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead style="background-color:#ABEBC6">
          <th>Ingrediente</th>
          <th>Cantidad necesaria</th>
          <th>Stock en inventario</th>
        </thead>
        @foreach($ingredientes_extras as $it)
        <tr>
          <td>{{$it->nombre}}</td>
          @if($it->unidad == 'gramos')
          <td>{{$it->sum/1000}} Kg</td>
          @else
          <td>{{round($it->sum)}} {{$it->unidad}}</td>
          @endif
          @if($it->inventareable == 1)
          <td>{{$it->stock}}</td>
          @else
          <td>No aplica inventario</td>
          @endif
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>
@endsection
