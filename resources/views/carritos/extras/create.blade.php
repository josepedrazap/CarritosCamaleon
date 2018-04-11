@extends ('layouts.admin')
@section('contenido')

<div class="row">
  <div class="col-lg-8 col-md-8 col-sm-8">
    <h3>Agregar extra</h3>
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

    {!!Form::open(array('url'=>'carritos/extras','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::Token()}}
    {{ csrf_field() }}
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
          <label>Nombre del extra</label>
          <input class="form-control" name="extra" required/>
        </div>
      </div>
    </div>

      <div class="form-group">
        <a href=""><button class="btn btn-primary" type="submit">Agregar extra</button></a>
        <button class="btn btn-danger" type="reset">Limpiar campos</button>
      </div>

    {!!Form::close()!!}


@endsection
