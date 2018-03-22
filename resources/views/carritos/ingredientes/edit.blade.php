@extends ('layouts.admin')
@section('contenido')

<script>
function calc(){
  v1 = $("#precio_bruto").val();
  document.getElementById('precio_liquido').value = v1 - v1*0.19;
  document.getElementById('iva').value = v1*0.19;
}
</script>

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Actualizar precio ingrediente</h3>

    @if(count($errors)>0)
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
      </ul>
    </div>
    @endif
  </div>
</div>
{!! Form::open(array('url'=>'carritos/ingredientes/cambiar_precio', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search'))!!}
    <input class="hidden" name="id" value="{{$id}}"/>
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
        <div class="form-group">
          <label for="nombre_producto">Nombre ingrediente</label>
          <input type="text" name="nombre" class="form-control" readonly="readonly" value="{{$ingrediente->nombre}}">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
        <div class="form-group">
          <label for="nombre_producto">Tipo</label>
          <input type="text" name="nombre" class="form-control" readonly="readonly" value="{{$ingrediente->tipo}}">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
        <div class="form-group">
          <label for="nombre_producto">Unidad de medida</label>
          <input type="text" name="nombre" class="form-control" readonly="readonly" value="{{$ingrediente->unidad}}">
        </div>
      </div>
    </div>

    <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
      <div class="form-group">
        <label for="tipo">Precio bruto</label>
        <input type="number" id="precio_bruto" onkeyup="calc()" class="form-control" required name="precio_bruto">
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
      <div class="form-group">
        <label for="tipo">IVA</label>
        <input type="number" id="iva" class="form-control" required name="iva" readonly="readonly">
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
      <div class="form-group">
        <label for="tipo">Precio líquido</label>
        <input type="number" id="precio_liquido" class="form-control" required name="precio_liquido" readonly="readonly">
      </div>
    </div>
  </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <div class="form-group">
        <a href="/carritos/ingredientes">
          <button class="btn btn-primary">Actualizar precio ingrediente</button>
        </a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>
    </div>

{{Form::close()}}


@endsection
