@extends ('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-lg-9 col-md-9 col-sm-6">
    @if($var == 1)
    <h3>Mercadería próximos ({{$cant_eventos}}) eventos entre {{$date_1}} y {{$date_2}}</h3>
    @else
    <h3>Seleccione un rango de fechas</h3>

    @endif
    <hr></hr>
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
        <?php $sum_total = 0; $cond = 0;?>
        @foreach($ingredientes_totales as $it)
        @foreach($ingredientes_extras as $ie)
        @if($it->id_ingr == $ie->id_ingr)
          @if($it->unidad == $ie->unidad)
            <?php $sum_total = $ie->sum + $it->sum; $ie->id_ingr = -1; $cond = 1;?>
          @endif
        @endif
        @endforeach
        <tr>
          <th>{{$it->nombre}}</th>
          @if($cond == 0)
            <?php $sum_total = $it->sum ?>
          @endif
          @if($it->unidad == 'gramos')
          <td>{{round($sum_total/1000,1)}} Kg</td>
          @else
          <td>{{round($sum_total)}} {{$it->unidad}}</td>
          @endif
          @if($it->inventareable == 1)
          @if($it->stock <= 0)
          <th style="color:orange">{{$it->stock}}</th>
          @else
          <th style="color:orange">{{$it->stock}}</th>
          @endif
          @else
          <td>No aplica inventario</td>
          @endif
        </tr>
        @endforeach
        @foreach($ingredientes_extras as $ie)
        @if($ie->id_ingr != -1)
        <tr>
          <th>{{$ie->nombre}}</th>
          @if($ie->unidad == 'gramos')
          <td>{{round($ie->sum/1000, 1)}} Kg</td>
          @else
          <td>{{round($ie->sum)}} {{$ie->unidad}}</td>
          @endif
          @if($ie->inventareable == 1)
          @if($ie->stock <= 0)
          <th style="color:orange">{{$ie->stock}}</th>
          @else
          <th style="color:blue">{{$ie->stock}}</th>
          @endif
          @else
          <td>No aplica inventario</td>
          @endif
        </tr>
        @endif
        @endforeach
      </table>
    </div>
  </div>
</div>
@endsection
