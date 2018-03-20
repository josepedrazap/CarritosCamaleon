@extends ('layouts.admin')
@section('contenido')

@if($resultado == 1)
  <h3>El usuario se ha creado con éxito.</h3>
@else
  <h3>ERROR: El usuario no se ha creado con éxito.</h3>
@endif

@endsection
