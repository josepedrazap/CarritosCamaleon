@extends ('layouts.admin')
@section('contenido')

<script>

var cont = 0;

function cargar_unidad(){
  var x = $("#id_item option:selected").val();
  @foreach($items as $ite)
  if({{$ite->id}} == x){
      document.getElementById('unidad').value = "{{$ite->unidad}}";
  }
  @endforeach
}
function addIngredientes(){

  item=$("#id_item option:selected").val();
  nom=$("#id_item option:selected").text();
  cantidad=$("#cantidad").val();

  if(item != "" &&  cantidad > 0){

    var fila = '<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onClick="eliminar('+cont+')">X</button></td><td><input hidden name="iditem[]" value="'+item+'"> <input class="form-control" disabled value="'+nom+'"></td><td><input class="form-control" type="number" name="cantidaditem[]" value="'+cantidad+'"></td><td><input class="form-control" type="text" disabled value="unidad" disabled></td></tr>';
    vaciar();
    $("#detalles").append(fila);
    $("#fila"+ index).remove();
    cont++;
    mostrar_buttons();
  }else{
    alert("Faltan campos por rellenar");
  }
}
function eliminar(index){

    sv = "#fila" + index;

    $(sv).remove();
    cont--;
    if(cont == 0){
      ocultar_buttons();
    }
    vaciar();
}
function vaciar(){
  $("#cantidad").val("");
}
function mostrar_buttons(){
    $("#save").show();
}
function ocultar_buttons(){
    $("#save").hide();
}
</script>

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Ingreso de inventario</h3>

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

    {!!Form::open(array('url'=>'carritos/inventario','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::Token()}}

    <div class="row">
      <div class="panel panel-primary">
        <div class="panel-body">
          <div class="col-lg-3 col-sm-3 col-md-12 col-xs-12">
            <div class="form-group">
              <label>Items</label>
              <select onchange="cargar_unidad()" name="id_item" class="form-control selectpicker" id="id_item" data-live-search="true">
                @foreach($items as $ite)
                  <option value="{{$ite->id}}">{{$ite->nombre}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
            <div class="form-group">
              <label>Cantidad</label>
              <input type="number"  name="cantidad"id="cantidad" class="form-control" placeholder="cantidad...">
            </div>
          </div>
          <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
            <div class="form-group">
              <label>Unidad</label>
              <input type="text" name="unidad" disabled id="unidad" class="form-control" placeholder="unidad...">
            </div>
          </div>
          <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
            <div class="form-group">
              <label>Agregar </label>
              <button type="button"  onClick="addIngredientes()" class="btn btn-primary">+</button>
            </div>
          </div>
          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
              <thead style="background-color:#A9D0F5">
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Unidad</th>
              </thead>
              <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><h4 id="total"></h4></th>
              </tfoot>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

      <div class="form-group" id="save">
        <input name="_token value={{csrf_token()}}" type="hidden"></input>
        <a href=""><button class="btn btn-primary" type="submit">Ingresar inventario</button></a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>

    {!!Form::close()!!}

@endsection
