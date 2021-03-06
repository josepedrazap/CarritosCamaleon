@extends ('layouts.admin')
@section('contenido')

<?php
  foreach($ayudas as $ay){
    if($ay->campo == 'precio')$ay_precio = $ay->texto;
    if($ay->campo == 'nombre_producto')$ay_nombre_producto = $ay->texto;
    if($ay->campo == 'plataforma')$ay_plataforma = $ay->texto;
    if($ay->campo == 'cant_ingrediente_')$ay_cant_ingrediente_ = $ay->texto;
    if($ay->campo == 'uni_')$ay_uni_ = $ay->texto;
  }
 ?>

<script>
e = 0;

function unidad(a){
  sv = "#select2" + a + " option:selected";
  var id = $(sv).val();
  sv2 = "unidad_id_"+ a;
  var Kg= "Kg";

  @foreach($ingredientes as $ingr)
  if({{$ingr->id}} == id){
    var uni = "{{$ingr->unidad}}";
    if( uni == Kg){
      document.getElementById(sv2).value = "gramos";
    }else{
      document.getElementById(sv2).value = "{{$ingr->unidad}}";
    }
  }
  @endforeach
}

function addIngredientes(){
      e++;

        if(e == 1){
          var div = document.createElement('div');
          div.innerHTML = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><label>Ingrediente</label></div><div class="col-md-3"><label>Porción sugerida</label></div><div class="col-md-3"><label>Medida</label></div>';
          document.getElementById('contenedor1').appendChild(div);
        }

        var div = document.createElement('div');
        div.setAttribute('class', 'form-inline');
            div.innerHTML ='<div class="ingredientes_'+e+' col-lg-6 col-md-6 col-sm-6 col-xs-6"><select id="select2'+e+'" class="form-control" onchange="unidad('+e+')" name="ingrediente_[]"></select></div><div class="ingrediente_'+e+' col-md-3""><input title="{{$ay_cant_ingrediente_}}" class="form-control" name="cant_ingrediente_[]" type="text"/></div><div class="col-md-2"><input title="{{$ay_uni_}}" class="form-control" readonly="readonly" name="uni_[]" id="unidad_id_'+e+'"/></div>';
            document.getElementById('ingredientes').appendChild(div);

            sv = "#select2"+e;
            llenar_select_extras(e,sv);

}
function llenar_select_extras(e, sv){
  tipo_ = "{{$ingredientes[0]->tipo}}";
  $(sv).append('<option>------------</option>');
  $(sv).append('<optgroup label="'+tipo_+'">');

  @foreach($ingredientes as $ing)
    id = "{{$ing->id}}";
    aux = "{{$ing->nombre}}";
    tipo = "{{$ing->tipo}}";

    if(tipo_ != tipo){
      $(sv).append('<optgroup/>');
      $(sv).append('<optgroup label="'+tipo+'">');
      tipo_ = "{{$ing->tipo}}";
    }
    $(sv).append('<option value="'+id+'">'+aux+'</option>');

  @endforeach
  $(sv).append('<optgroup/>');
}
</script>

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Crear nuevo producto</h3>

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


    {!!Form::open(array('url'=>'carritos/productos','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::Token()}}

    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
        <div class="form-group">
          <label for="nombre_producto">Nombre del producto</label>
          <input type="text" title="{{$ay_nombre_producto}}" name="nombre_producto" required class="form-control" placeholder="nombre...">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 hidden">
        <div class="form-group">
          <label for="tipo">Tipo</label>
          <select class="form-control" name="tipo">
            @foreach($tipos as $tp)
            <option>{{$tp->valor}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
        <div class="form-group">
          <label for="precio">Precio neto sugerido</label>
          <input  title="{{$ay_precio}}" name="precio" type="number" class="form-control" required placeholder="precio...">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 hidden">
        <div class="form-group">
          <label for="plataforma">Base</label>
          <select title="{{$ay_plataforma}}" class="form-control" name="plataforma">
            @foreach($bases as $bs)
            <option value="{{$bs->valor}}" >{{$bs->valor}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
          <h3>Ingredientes cada 100 unidades de productos
          </h3>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <div class="form-group">
        <input type="button" class="btn btn-success" onClick="addIngredientes()" value="+ Agregar ingrediente" />
        </div>
      </div>
      <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
        <div class="form-group">
        <input type="button" class="btn btn-danger" onClick="lessIngrediente()" value="-" />
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
        <div class="form-group">
          <div class="row" id="contenedor1">
          </div>
          <div class="row" id="ingredientes">
          </div>
        </div>
      </div>
    </div>

      <div class="form-group">
        <input name="_token value={{csrf_token()}}" type="hidden"></input>
        <a href=""><button class="btn btn-primary" type="submit">Crear producto</button></a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>

    {!!Form::close()!!}


@endsection
