@extends ('layouts.admin')
@section('contenido')

<script>

a = 0;
e = 0;
u = 0;
aux_1 = 0;
aux_2 = 0;
aux_3 = 0;
function eval(){
    $("#save").show();
}
function hide_(){
  $("#save").hide();
}
function eliminar_producto(){
  if(a > 0){
    sv_1 = "#prod_" + a;
    sv_2 = "#prod_cant_" + a;
    $(sv_1).remove();
    $(sv_2).remove();
    if(a == 1){
        $("#nom_prod").remove();
        $("#nom_prod_cant").remove();
        aux_1 = 0;
        hide_();
    }
    a--;
  }
}
function eliminar_extra(){
  if(e > 0){
    sv_1 = "#extra_" + e;
    sv_2 = "#extra_cant_" + e;
    $(sv_1).remove();
    $(sv_2).remove();
    if(e == 1){
        $("#nom_ext").remove();
        $("#nom_ext_cant").remove();
        aux_2 = 0;
    }
    e--;
  }
}
function eliminar_extra_ingr(){
  if(u > 0){
    sv_1 = "#extra_ingr_" + u;
    sv_2 = "#ingr_cant_" + u;
    $(sv_1).remove();
    $(sv_2).remove();
    if(u == 1){
        $("#nom_ext_ingr").remove();
        $("#nom_ext_ingr_cant").remove();
        aux_3 = 0;
    }
    u--;
  }
}
function cargar_cliente(){
    var x = $("#cliente option:selected").val();
    @foreach($clientes as $cli)
    if({{$cli->id}} == x){
        document.getElementById('nombre_cliente').value = "{{$cli->nombre}} {{$cli->apellido}}";
        document.getElementById('telefono_cliente').value = "{{$cli->contacto}}";
        document.getElementById('email_cliente').value = "{{$cli->mail}}";
    }
    @endforeach
}
function addProducto(){
      a++;
        if(a == 1 && aux_1 != 1){
          var div = document.createElement('div');
          div.innerHTML = '<div id="nom_prod" class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><label>Producto</label></div><div id="nom_prod_cant" class="col-md-2"><label>Cantidad</label></div>';
          document.getElementById('contenedor1').appendChild(div);
          aux_1 = 1;
        }

        var div = document.createElement('div');
        div.setAttribute('class', 'form-inline');
            div.innerHTML ='<div id="prod_'+a+'" class="producto_'+a+' col-lg-6 col-md-6 col-sm-6 col-xs-6"><select id="select'+a+'" class="form-control" name="producto[]"></select></div><div id="prod_cant_'+a+'" class="producto_'+a+' col-xs-6"><input required class="form-control" name="cant_producto[]" type="number"/></div>';
            document.getElementById('productos').appendChild(div);
            eval();
            sv = "#select"+a;
            llenar_select_productos(a,sv);
}
function llenar_select_productos(a, sv){
  @foreach($productos as $p)
    id = "{{$p->id}}";
    aux = "{{$p->nombre}} (${{$p->precio}})";
    $(sv).append('<option value="'+id+'">'+aux+'</option>');
  @endforeach
}
function addExtras(){
      e++;
        if(e == 1 && aux_2 == 0){
          var div = document.createElement('div');
          div.innerHTML = '<div id="nom_ext" class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><label>Extras</label></div>';
          document.getElementById('contenedor2').appendChild(div);
          aux_2 = 1;
        }
        var div = document.createElement('div');
        div.setAttribute('class', 'form-inline');
            div.innerHTML ='<div id="extra_'+e+'" style="clear:both" class="extras_'+e+' col-lg-6 col-md-6 col-sm-6 col-xs-6"><select id="select2'+e+'" class="form-control" name="extra[]"></select></div>';
            document.getElementById('extras').appendChild(div);
            sv = "#select2"+e;
            llenar_select_extras(e,sv);

}
function addingr_Extras(){
      u++;
        if(u == 1 && aux_3 == 0){
          var div = document.createElement('div');
          div.innerHTML = '<div id="nom_ext_ingr" class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><label>Ingrediente</label></div><div id="nom_ext_ingr_cant" class="col-md-2"><label>Cantidad</label></div>';
          document.getElementById('contenedor3').appendChild(div);
          aux_3 = 1;
        }
        var div = document.createElement('div');
        div.setAttribute('class', 'form-inline');
            div.innerHTML ='<div id="extra_ingr_'+u+'" style="clear:both" class="extras_ingr_'+u+' col-lg-6 col-md-6 col-sm-6 col-xs-6"><select id="select3'+u+'" class="form-control" name="extra_ingr[]"></select></div><div id="ingr_cant_'+u+'" class="col-lg-1"><input required class="form-control" name="cant_ingr[]" type="text"/></div>';
            document.getElementById('extras_ingr').appendChild(div);
            sv = "#select3"+u;
            llenar_select_ingr_extras(u,sv);

}

function llenar_select_ingr_extras(u, sv){

  tipo_ = "{{$ingredientes[0]->tipo}}";
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
function llenar_select_extras(e, sv){
  @foreach($extras as $ext)
    id = "{{$ext->id}}";
    aux = "{{$ext->valor}}";
    $(sv).append('<option value="'+id+'">'+aux+'</option>');
  @endforeach
}
</script>

    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <h3>Crear evento</h3>
        <hr/>
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

    {!!Form::open(array('url'=>'carritos/eventos','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::Token()}}

    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <h4>Datos del evento <input name="condicion" value="1" type="hidden"></h4>
      </div>
    </div>

    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-9">
          <div class="form-group">
            <label for="nombre_cliente">Cliente</label>
            <select onchange="cargar_cliente()" id="cliente" class="form-control selectpicker" name="cliente" data-live-search="true">
              @foreach($clientes as $cli)
              <option value="{{$cli->id}}">{{$cli->nombre}} {{$cli->apellido}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-3 col-xs-3">
          <div class="form-group">
            <label for="nombre_cliente">Nuevo</label>
            <a href="/carritos/clientes/create"><button type="button" class="btn btn-info">Nuevo</button></a>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <div class="form-group">
            <label for="nombre_cliente">Nombre cliente</label>
            <input type="text" id="nombre_cliente" class="form-control" disabled placeholder="nombre...">
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="form-group">
            <label for="telefono_cliente">Teléfono</label>
            <input type="tel" id="telefono_cliente" disabled class="form-control" placeholder="+569...">
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="form-group">
            <label for="email_cliente">E-mail</label>
            <input type="text" id="email_cliente" disabled class="form-control" placeholder="e-mail...">
          </div>
        </div>
      <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
        <div class="form-group">
          <label for="direccion_cliente">Dirección del evento</label>
          <input type="text" name="direccion_cliente" class="form-control" required placeholder="dirección...">
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="form-group">
          <label for="fecha_cliente">Fecha y hora</label>
          <input type="datetime-local" name="fecha_cliente" class="form-control" placeholder="fecha..." required>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="form-group">
          <label for="fecha_despacho">Hora despacho</label>
          <input type="datetime-local" name="fecha_despacho" class="form-control" placeholder="hora..." required>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
          <label for="comentarios">Descripción</label>
          <textarea name="descripcion" class="form-control" rows="6" required placeholder="Descripción del evento"></textarea>
        </div>
      </div>

    </div>


    <div class="row">
      <hr></hr>
      <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
        <h4>Agregar producto</h4>
        <input type="button" class="btn btn-success" onClick="addProducto()" value="+ Agregar producto" />
        <input type="button" class="btn btn-danger" onClick="eliminar_producto()" value="X" />
        </div>
        <div class="form-group">
          <div class="row" id="contenedor1">
          </div>
          <div class="row" id="productos">
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
        <div class="form-group">
        <h4>Agregar ingrediente extra</h4>
        <input type="button" class="btn btn-success" onClick="addingr_Extras()" value="+ Agregar ingrediente extra" />
        <input type="button" class="btn btn-danger"   onClick="eliminar_extra_ingr()" value="X" />
        </div>
        <div class="form-group">
          <div class="row" id="contenedor3">
          </div>
          <div class="row" id="extras_ingr">
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
        <div class="form-group">
        <h4>Agregar extra</h4>
        <input type="button" class="btn btn-success" onClick="addExtras()" value="+ Agregar extra" />
        <input type="button" class="btn btn-danger"   onClick="eliminar_extra()" value="X" />
        </div>
        <div class="form-group">
          <div class="row" id="contenedor2">
          </div>
          <div class="row" id="extras">
          </div>
        </div>
      </div>
    </div>

    <div class="form-group" id="save" hidden>
      <a href=""><button class="btn btn-primary" type="submit">Crear evento</button></a>
      <button class="btn btn-danger" type="reset">Limpiar campos</button>
    </div>

{!!Form::close()!!}
@endsection
