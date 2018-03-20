@extends ('layouts.admin')
@section('contenido')
<script>
  function iva_calc(){
    if (document.getElementById('id_iva').checked){
      v1 = $("#monto").val();
      document.getElementById('total_final').value = parseFloat(v1 - v1*0.19);
      document.getElementById('total_iva').value = parseFloat(v1*0.19);
    }else{
      v1 = $("#monto").val();
      document.getElementById('total_final').value = parseFloat(v1);
      document.getElementById('total_iva').value = 0;
    }
  }
</script>
<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Ingresar gasto</h3>
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

    {!!Form::open(array('url'=>'carritos/gastos','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::Token()}}

    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <h4>Datos del gasto</h4>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <label for="tipo">Cuenta</label>
          <div class="form-group">
            <select class="form-control" name="tipo" required>
              @foreach($gst as $gt)
              <option>{{$gt->nombre}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <label for="fecha">Fecha</label>
          <div class="form-group">
            <input name="fecha" type="date" class="form-control" required></input>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <label for="fecha">Nombre pagador</label>
          <div class="form-group">
            <input name="nombre_pagador" type="text" class="form-control" value="Empresa" required></input>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <label for="apellido">Monto</label>
          <div class="input-group">
            <span class="input-group-addon">
              <label>Aplica IVA <input type="checkbox" id="id_iva" onclick="iva_calc();" aria-label="..."></label>
            </span>
            <input type="text" class="form-control" name="monto" id="monto" onkeyup="iva_calc();" required>
          </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <label for="iva">Total IVA</label>
          <div class="input-group">
            <span class="input-group-addon">$</span>
            <input id="total_iva" name="iva" class="form-control" required ></input>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <label for="total">Total final</label>
          <div class="input-group">
            <span class="input-group-addon">$</span>
            <input id="total_final" name="total" class="form-control" required ></input>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <div class="input-group">
            <input class="hidden" class="form-control"></input>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
          <hr></hr>
          <div class="form-group">
            <label for="comentarios">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="6" required placeholder="Descripción del gasto"></textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <div class="form-group">
        <a href=""><button class="btn btn-primary" type="submit">Ingresar gasto</button></a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>
    </div>
  </div>

    {!!Form::close()!!}


@endsection
