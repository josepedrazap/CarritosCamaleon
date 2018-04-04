@extends ('layouts.admin')
@section('contenido')

@if($resultado == 2)
<h3>El usuario se ha actualizado con éxito. {{$user->name}}</h3>

@endif
@if($resultado == 1)
  <h3>El usuario se ha creado con éxito.</h3>
@else
  <h3>ERROR: El usuario no se ha creado con éxito.</h3>
@endif

@endsection
