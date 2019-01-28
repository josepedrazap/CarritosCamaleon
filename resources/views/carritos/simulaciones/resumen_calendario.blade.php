<head>
  <script>
    function submit(i){
      window.open('/simulacion_resumen?id=' + i, '_blank');
      window.close();
    }
  </script>
</head>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<div class="row">
  <div class="col-md-6">
    <table class="table table-striped table-bordered table-condensed table-hover">
      @foreach($productos as $prods)
      <tr>
        <td>{{$prods->cantidad}} {{$prods->nombre}} a ${{$prods->precio_neto_unidad}} netos</td>
      </tr>
      @endforeach
      @foreach($ingredientes_extras as $ingr_ext)
      @if($ingr_ext->unidad == "Kg")
      <tr>
        <td>{{$ingr_ext->cantidad * $ingr_ext->porcion_unitaria / 1000}} {{$ingr_ext->unidad}} de {{$ingr_ext->nombre}} con un costo de ${{$ingr_ext->costo_neto_unidad}} netos vendidos en ${{$ingr_ext->precio_neto_unidad}} netos</td>
      </tr>
      @else
      <tr>
        <td>{{$ingr_ext->cantidad * $ingr_ext->porcion_unitaria}} {{$ingr_ext->unidad}}(s) de {{$ingr_ext->nombre}} con un costo de ${{$ingr_ext->costo_neto_unidad}} netos vendidos en ${{$ingr_ext->precio_neto_unidad}} netos</td>
      </tr>
      @endif
      @endforeach
      @foreach($extras as $ext)
      <tr>
        <td>{{$ext->cantidad}} {{$ext->valor}}(s) con un costo de ${{$ext->cantidad * $ext->costo_neto_unidad}} netos vendidos en ${{$ext->precio_neto_unidad}} netos</td>
      </tr>
      @endforeach
      @foreach($nuevos as $nue)
      <tr>
        <td>{{$nue->cantidad}} {{$nue->nombre}} con un costo de ${{$nue->cantidad * $nue->costo_neto_unidad}} netos vendido en ${{$nue->cantidad * $nue->precio_neto_unidad}} netos</td>
      </tr>
      @endforeach

      @foreach($trabajadores as $tra)
      <tr>
        <td>{{$tra->cantidad}} {{$tra->nombre}}(s) a un total de ${{$tra->sueldo}} líquidos</td>
      </tr>
      @endforeach
    </table>
  </div>
  <div class="col-md-6">
    <textarea class="form-control" name="descripcion" rows="3">{{$sim[0]->descripcion}}</textarea>
  </div>
</div>
<hr></hr>
<div class="row">
  <div class="col-md-6">
    <table class="table table-striped table-bordered table-condensed table-hover">
      <tr>
        <th>Total Neto</th>
        <th>Iva</th>
        <th>Monto a cobrar (Bruto)</th>
      </tr>
      <tr>
        <td>${{$simulacion[0]->total_final_neto}}</td>
        <td>${{$simulacion[0]->total_final_iva}}</td>
        <td>${{$simulacion[0]->total_final_bruto}}</td>
      </tr>
    </table>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <a><button onclick="submit({{$id}})" class="btn btn-primary">Generar evento</button></a>
  </div>
</div>
