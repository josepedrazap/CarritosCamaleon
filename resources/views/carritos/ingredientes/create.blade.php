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
    <h3>Crear nuevo ingrediente</h3>

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

    {!!Form::open(array('url'=>'carritos/ingredientes','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::Token()}}

    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
        <div class="form-group">
          <label for="nombre_producto">Nombre ingrediente</label>
          <input type="text" name="nombre" class="form-control" placeholder="nombre..." required>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
        <div class="form-group">
          <label for="tipo">Tipo</label>
          <select class="form-control" name="tipo">
            <option>verdura</option>
            <option>lacteo</option>
            <option>proteina</option>
            <option>masa</option>
            <option>refrigerio</option>
          </select>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
        <div class="form-group">
          <label for="tipo">Unidad de medida</label>
          <select class="form-control" name="unidad">
            <option>Kg</option>
            <option>unidad</option>
            <option>lámina</option>
            <option>caja</option>
            <option>bolsa</option>
          </select>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
        <div class="btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-primary desactive">
            <input name="inventareable" type="checkbox"> Inventareable?
            </label>
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
        <a href=""><button class="btn btn-primary" type="submit">Crear ingrediente</button></a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>
    </div>


    {!!Form::close()!!}


@endsection
